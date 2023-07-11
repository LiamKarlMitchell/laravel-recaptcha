<?php

/**
 * Copyright (c) 2017 - present
 * LaravelGoogleRecaptcha - ReCaptchaBuilderV3.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 22/1/2019
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

namespace Biscolab\ReCaptcha;

use Illuminate\Support\Arr;

/**
 * Class ReCaptchaBuilderV3
 * @package Biscolab\ReCaptcha
 */
class ReCaptchaBuilderV3 extends ReCaptchaBuilder
{

    /**
     * ReCaptchaBuilderV3 constructor.
     *
     * @param string $api_site_key
     * @param string $api_secret_key
     */
    public function __construct(string $api_site_key, string $api_secret_key)
    {

        parent::__construct($api_site_key, $api_secret_key, 'v3');
    }

    public function getTokenParameterName(): string
    {
        return config(
            'recaptcha.default_token_parameter_name',
            'token'
        );
    }

    public function getValidationUrl(): string
    {
        return url(config(
            'recaptcha.default_validation_route',
            'biscolab-recaptcha/validate'
        ));
    }

    public function getValidationUrlWithToken(): string
    {
        return implode(
            "?",
            [
                $this->getValidationUrl(),
                $this->getTokenParameterName()
            ]
        );
    }

    /**
     * Write script HTML tag in you HTML code
     * Insert before </head> tag
     *
     * I suspect that this is used to inform reCAPTCHA about the page load.
     *
     * @param array|null $configuration
     *
     * @return string
     */
    public function htmlScriptTagJsApi(?array $configuration = []): string
    {

        if ($this->skip_by_ip) {
            return '';
        }

        $html = "<script src=\"" . $this->api_js_url . "?render={$this->api_site_key}\"></script>";

        $action = Arr::get($configuration, 'action', 'homepage');

        $js_custom_validation = Arr::get($configuration, 'custom_validation', '');

        // Check if set custom_validation. That function will override default fetch validation function
        if ($js_custom_validation) {

            $validate_function = ($js_custom_validation) ? "{$js_custom_validation}(token);" : '';
        } else {

            $js_then_callback = Arr::get($configuration, 'callback_then', '');
            $js_callback_catch = Arr::get($configuration, 'callback_catch', '');

            $js_then_callback = ($js_then_callback) ? "{$js_then_callback}(response)" : '';
            $js_callback_catch = ($js_callback_catch) ? "{$js_callback_catch}(err)" : '';

            $validate_function = "
                fetch('" . $this->getValidationUrlWithToken() . "=' + token, {
                    headers: {
                        \"X-Requested-With\": \"XMLHttpRequest\",
                        \"X-CSRF-TOKEN\": csrfToken.content
                    }
                })
                .then(function(response) {
                   	{$js_then_callback}
                })
                .catch(function(err) {
                    {$js_callback_catch}
                });";
        }

        $html .= "<script>
                    var csrfToken = document.head.querySelector('meta[name=\"csrf-token\"]');
                  grecaptcha.ready(function() {
                      grecaptcha.execute('{$this->api_site_key}', {action: '{$action}'}).then(function(token) {
                        {$validate_function}
                      });
                  });
		     </script>";

        return $html;
    }

    /**
     * Writes a HTML script tag that exposes a ReCaptchaV3 object for resolving the reCAPTCHA token.
     * Insert this before the closing </head> tag, following the htmlScriptTagJsApi call, as it does not load the reCAPTCHA script.
     *
     * The ReCaptchaV3 object in JavaScript has a method called execute that returns a promise resolving with a reCAPTCHA token.
     *   - action: string, defaults to 'homepage'.
     *     You may set this to a specific action, such as "contact_form_submit", based on the user's action.
     *
     * @return string The generated script HTML tag.
     */
    public function htmlScriptTagJsObjectV3(): string
    {
        $html = '';
        if ($this->skip_by_ip) {
            $html .= "<script>
                  ReCaptchaV3 = {
                      execute: async (action = 'homepage') => return 'skip_by_ip'
                  };
		     </script>";
            return $html;
        }

        $html .= "<script>
                  ReCaptchaV3 = {
                      execute: async (action = 'homepage') => {
                          return new Promise((resolve, reject) => {
                              grecaptcha.ready(function() {
                                  grecaptcha.execute('{$this->api_site_key}', {action: action})
                                  .then(token => resolve(token))
                                  .catch(err => reject(err));
                              })
                          });
                      }
                    };
		     </script>";

        return $html;
    }

    /***
     * The same as htmlScriptTagJsObjectV3 but it loads the reCAPTCHA script if the user is not skipped by IP.
     * Can be used if you only want to include on specific pages but not send on page load.
     *
     * @return string
     */
    public function htmlScriptTagJsObjectV3WithDependency(): string
    {
        $html = '';
        if (!$this->skip_by_ip) {
            $html = "<script src=\"".$this->api_js_url."?render={$this->api_site_key}\"></script>";
            return $html;
        }
        $html .= $this->htmlScriptTagJsObjectV3();

        return $html;
    }

}
