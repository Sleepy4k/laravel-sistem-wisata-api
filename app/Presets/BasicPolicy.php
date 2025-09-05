<?php

namespace App\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Value;

class BasicPolicy implements Preset
{
    /**
     * Configure csp policies for general and other policy
     *
     * @return void
     */
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::BASE, Directive::DEFAULT], Keyword::NONE)
            ->add([Directive::FORM_ACTION, Directive::MEDIA, Directive::FRAME], Keyword::NONE)
            ->add([Directive::MANIFEST, Directive::CHILD, Directive::CONNECT], Keyword::NONE)
            ->add([Directive::WORKER, Directive::OBJECT, Directive::WEB_RTC], Keyword::NONE)
            ->add([Directive::SCRIPT, Directive::STYLE, Directive::FONT], Keyword::NONE)
            ->add([Directive::SCRIPT_ELEM, Directive::STYLE_ELEM], Keyword::NONE)
            ->add([Directive::SCRIPT_ATTR, Directive::STYLE_ATTR], Keyword::NONE)
            ->add([Directive::BLOCK_ALL_MIXED_CONTENT], Value::NO_VALUE);

        if (app()->environment(['production', 'staging'])) {
            $policy
                ->add([Directive::UPGRADE_INSECURE_REQUESTS], Value::NO_VALUE);
        }
    }
}
