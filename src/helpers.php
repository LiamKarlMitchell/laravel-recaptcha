<?php

/**
 * Copyright (c) 2017 - present
 * LaravelGoogleRecaptcha - helpers.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 12/9/2018
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

use Biscolab\ReCaptcha\Facades\ReCaptcha;

if (!function_exists('recaptcha')) {
    /**
     * @return Biscolab\ReCaptcha\ReCaptchaBuilder|\Biscolab\ReCaptcha\ReCaptchaBuilderV2|\Biscolab\ReCaptcha\ReCaptchaBuilderInvisible|\Biscolab\ReCaptcha\ReCaptchaBuilderV3
     */
    function recaptcha(): \Biscolab\ReCaptcha\ReCaptchaBuilder
    {

        return app('recaptcha');
    }
}

/**
 * call ReCaptcha::htmlScriptTagJsApi()
 * Write script HTML tag in you HTML code
 * Insert before </head> tag
 *
 * @param $config ['form_id'] required if you are using invisible ReCaptcha
 */
if (!function_exists('htmlScriptTagJsApi')) {

    /**
     * @param array|null $config
     *
     * @return string
     */
    function htmlScriptTagJsApi(?array $config = []): string
    {

        return ReCaptcha::htmlScriptTagJsApi($config);
    }
}


if (!function_exists('htmlScriptTagJsObjectV3')) {
    /**
     * Writes a HTML script tag that exposes a ReCaptchaV3 object for resolving the reCAPTCHA token.
     * Insert this before the closing </head> tag, following the htmlScriptTagJsApi call, as it does not load the reCAPTCHA script.
     *
     * The ReCaptchaV3 object in JavaScript has a method called execute that returns a promise resolving with a reCAPTCHA token.
     *   - action: string, defaults to 'homepage'.
     *     You may set this to a specific action, such as "contact_form_submit", based on the user's action.
     *
     * Note: This is only valid for v3.
     *
     * @return string The generated script HTML tag.
     */
    function htmlScriptTagJsObjectV3(): string
    {
        return ReCaptcha::htmlScriptTagJsObjectV3();
    }
}


if (!function_exists('htmlScriptTagJsObjectV3WithDependency')) {
    /***
     * The same as htmlScriptTagJsObjectV3 but it loads the reCAPTCHA script if the user is not skipped by IP.
     * Can be used if you only want to include on specific pages but not send on page load.
     *
     * @return string
     */
    function htmlScriptTagJsObjectV3WithDependency(): string
    {
        return ReCaptcha::htmlScriptTagJsObjectV3WithDependency();
    }
}


/**
 * call ReCaptcha::htmlFormButton()
 * Write HTML <button> tag in your HTML code
 * Insert before </form> tag
 *
 * Warning! Using only with ReCAPTCHA INVISIBLE
 *
 * @param $buttonInnerHTML What you want to write on the submit button
 */
if (!function_exists('htmlFormButton')) {

    /**
     * @param null|string $button_label
     * @param array|null  $properties
     *
     * @return string
     */
    function htmlFormButton(?string $button_label = 'Submit', ?array $properties = []): string
    {

        return ReCaptcha::htmlFormButton($button_label, $properties);
    }
}

/**
 * call ReCaptcha::htmlFormSnippet()
 * Write ReCAPTCHA HTML tag in your FORM
 * Insert before </form> tag
 *
 * Warning! Using only with ReCAPTCHA v2
 */
if (!function_exists('htmlFormSnippet')) {

    /**
     * @param null|array $attributes
     * @return string
     */
    function htmlFormSnippet(?array $attributes = []): string
    {

        return ReCaptcha::htmlFormSnippet($attributes);
    }
}

/**
 * call ReCaptcha::getFormId()
 * return the form ID
 * Warning! Using only with ReCAPTCHA invisible
 */
if (!function_exists('getFormId')) {

    /**
     * @return string
     */
    function getFormId(): string
    {

        return ReCaptcha::getFormId();
    }
}

/**
 * return ReCaptchaBuilder::DEFAULT_RECAPTCHA_RULE_NAME value ("recaptcha")
 * Use V2 (checkbox and invisible)
 */
if (!function_exists('recaptchaRuleName')) {

    /**
     * @return string
     */
    function recaptchaRuleName(): string
    {

        return \Biscolab\ReCaptcha\ReCaptchaBuilder::DEFAULT_RECAPTCHA_RULE_NAME;
    }
}

/**
 * return ReCaptchaBuilder::DEFAULT_RECAPTCHA_FIELD_NAME value "g-recaptcha-response"
 * Use V2 (checkbox and invisible)
 */
if (!function_exists('recaptchaFieldName')) {

    /**
     * @return string
     */
    function recaptchaFieldName(): string
    {

        return \Biscolab\ReCaptcha\ReCaptchaBuilder::DEFAULT_RECAPTCHA_FIELD_NAME;
    }
}
