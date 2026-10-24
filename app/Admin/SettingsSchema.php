<?php

namespace App\Admin;

class SettingsSchema
{
    public static function groups(): array
    {
        return [
            'General' => [
                'site_name' => ['type' => 'text', 'label' => 'Company name'],
                'site_tagline' => ['type' => 'text', 'label' => 'Tagline'],
                'email' => ['type' => 'email', 'label' => 'Public email'],
                'phone' => ['type' => 'text', 'label' => 'Phone'],
                'whatsapp' => ['type' => 'text', 'label' => 'WhatsApp number', 'help' => 'International format without + or spaces, e.g. 2348012345678. Leave empty to hide the WhatsApp button.'],
                'address' => ['type' => 'textarea', 'label' => 'Office address'],
                'hours' => ['type' => 'text', 'label' => 'Office hours'],
                'map_query' => ['type' => 'text', 'label' => 'Map location', 'help' => 'What to search on Google Maps for the contact page map, e.g. "Zoo Road, Kano".'],
                'rc_number' => ['type' => 'text', 'label' => 'CAC registration number', 'help' => 'Shown in the footer when filled.'],
            ],
            'Home page' => [
                'hero_title' => ['type' => 'text', 'label' => 'Main headline'],
                'hero_text' => ['type' => 'textarea', 'label' => 'Text under the headline'],
                'hero_primary' => ['type' => 'text', 'label' => 'Main button text'],
                'hero_secondary' => ['type' => 'text', 'label' => 'Second button text'],
                'stats' => ['type' => 'list', 'label' => 'Figures strip', 'help' => 'One per line as "figure | label", e.g. "40+ | systems delivered". Only add figures you can stand behind. Leave empty to hide the strip.'],
                'cta_title' => ['type' => 'text', 'label' => 'Closing call-to-action heading'],
                'cta_text' => ['type' => 'textarea', 'label' => 'Closing call-to-action text'],
            ],
            'About page' => [
                'about_intro' => ['type' => 'textarea', 'label' => 'Opening paragraph'],
                'about_story' => ['type' => 'markdown', 'label' => 'Our story'],
                'mission' => ['type' => 'textarea', 'label' => 'Mission'],
                'vision' => ['type' => 'textarea', 'label' => 'Vision'],
                'values' => ['type' => 'list', 'label' => 'Values', 'help' => 'One per line as "value | what it means".'],
            ],
            'Social links' => [
                'social_linkedin' => ['type' => 'url', 'label' => 'LinkedIn'],
                'social_x' => ['type' => 'url', 'label' => 'X (Twitter)'],
                'social_facebook' => ['type' => 'url', 'label' => 'Facebook'],
                'social_instagram' => ['type' => 'url', 'label' => 'Instagram'],
                'social_github' => ['type' => 'url', 'label' => 'GitHub'],
                'social_youtube' => ['type' => 'url', 'label' => 'YouTube'],
            ],
            'SEO and tracking' => [
                'seo_title_suffix' => ['type' => 'text', 'label' => 'Title suffix', 'help' => 'Added after every page title, e.g. "| Onyx Innovations".'],
                'seo_description' => ['type' => 'textarea', 'label' => 'Default description'],
                'seo_keywords' => ['type' => 'text', 'label' => 'Keywords'],
                'google_analytics' => ['type' => 'text', 'label' => 'Google Analytics ID', 'help' => 'Looks like G-XXXXXXX.'],
                'google_verification' => ['type' => 'text', 'label' => 'Google Search Console code', 'help' => 'Only the content value of the verification meta tag.'],
                'bing_verification' => ['type' => 'text', 'label' => 'Bing Webmaster code'],
            ],
            'Notifications' => [
                'notify_email' => ['type' => 'email', 'label' => 'Send new enquiries to', 'help' => 'Needs mail settings in the .env file. Enquiries are always saved in the admin either way.'],
            ],
            'Legal pages' => [
                'privacy_policy' => ['type' => 'markdown', 'label' => 'Privacy policy'],
                'terms' => ['type' => 'markdown', 'label' => 'Terms of use'],
            ],
        ];
    }

    public static function fields(): array
    {
        return array_merge(...array_values(static::groups()));
    }
}
