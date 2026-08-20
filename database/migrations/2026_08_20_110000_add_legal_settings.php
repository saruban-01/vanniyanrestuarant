<?php

use App\Models\RestaurantSetting;
use App\Models\SeoMetadata;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Legal documents live in the central RestaurantSetting system so they
     * are editable from the Admin panel, cacheable, and never require a
     * redeploy. Defaults are published immediately so the public pages are
     * never blank; the Admin Legal editor carries the review disclaimer.
     *
     * The default text describes the ACTUAL functionality of the website
     * (takeaway pickup, table reservations, venue hire, contact form,
     * Google Reviews display, admin-controlled GTM/Meta/analytics). It is
     * a website draft and must be reviewed by a qualified legal
     * professional before being relied on as formal terms.
     */
    public function up(): void
    {
        $today = now('Asia/Colombo')->toDateString();

        $defaults = [
            'legal_privacy_published' => self::privacyPolicy(),
            'legal_privacy_draft' => '',
            'legal_privacy_published_at' => $today,
            'legal_privacy_updated_by' => '0',
            'legal_terms_published' => self::termsConditions(),
            'legal_terms_draft' => '',
            'legal_terms_published_at' => $today,
            'legal_terms_updated_by' => '0',
            'legal_governing_law' => 'Sri Lanka',
        ];

        foreach ($defaults as $key => $value) {
            RestaurantSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // SEO metadata for the three new public, indexable pages.
        $seoDefaults = [
            [
                'route_name' => 'privacy-policy',
                'meta_title' => 'Vanniyan Restaurant Privacy Policy',
                'meta_description' => 'Learn how Vanniyan Restaurant collects, uses and protects information when you use our website and services.',
            ],
            [
                'route_name' => 'terms-and-conditions',
                'meta_title' => 'Vanniyan Restaurant Terms & Conditions',
                'meta_description' => 'Read the terms that apply to using the Vanniyan Restaurant website, takeaway orders, bookings and venue services.',
            ],
            [
                'route_name' => 'sitemap.page',
                'meta_title' => 'Vanniyan Restaurant Sitemap',
                'meta_description' => 'Explore the Vanniyan Restaurant website and find our menu, offers, booking, stories, contact information and legal pages.',
            ],
        ];

        foreach ($seoDefaults as $row) {
            SeoMetadata::firstOrCreate(
                ['route_name' => $row['route_name']],
                $row + ['robots' => 'index, follow'],
            );
        }
    }

    public function down(): void
    {
        RestaurantSetting::whereIn('key', [
            'legal_privacy_published',
            'legal_privacy_draft',
            'legal_privacy_published_at',
            'legal_privacy_updated_by',
            'legal_terms_published',
            'legal_terms_draft',
            'legal_terms_published_at',
            'legal_terms_updated_by',
            'legal_governing_law',
        ])->delete();

        SeoMetadata::whereIn('route_name', ['privacy-policy', 'terms-and-conditions', 'sitemap.page'])->delete();
    }

    private static function privacyPolicy(): string
    {
        return '
<h2 id="introduction">Introduction</h2>
<p>Vanniyan Restaurant (&ldquo;Vanniyan&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo; or &ldquo;our&rdquo;) operates this website to help guests explore our menu, place takeaway orders, book tables, request venue space and get in touch with us. This Privacy Policy explains what information we collect through the website, why we collect it, and how it is handled.</p>
<p>By using this website, you agree to the practices described in this policy. If you do not agree with any part of it, please do not use the website.</p>
<h2 id="information-we-collect">Information We Collect</h2>
<p>We collect only the information that the website actually needs to function. We collect two broad types of information: information you provide to us, and information collected automatically as you browse.</p>
<h2 id="information-you-provide">Information You Provide</h2>
<p>When you use our online services, you may provide:</p>
<ul>
<li>your name</li>
<li>your mobile number</li>
<li>your email address, where you choose to provide it (email is optional for most services)</li>
<li>details of your takeaway order, including the items selected and your preferred pickup time</li>
<li>details of your table reservation, including the date, time and number of guests</li>
<li>details of your venue booking request, including the event date, guest count and the services you are interested in</li>
<li>special requests you include with an order, reservation or booking</li>
<li>the contents of messages you send through our contact form</li>
</ul>
<p>We do not require you to create an account, and we do not collect payment card details online &mdash; takeaway orders are paid for at pickup.</p>
<h2 id="information-collected-automatically">Information Collected Automatically</h2>
<p>Like most websites, we may automatically collect limited technical information when you visit, including your IP address, browser and device type, the pages you visit and the approximate time of your visit. Where analytics is enabled (see <a href="#analytics-and-marketing">Analytics and Marketing</a>), event data may describe how visitors interact with the menu, cart, booking and ordering features. This information is used in aggregate to understand how the website is used; we do not use it to identify individual visitors by name.</p>
<h2 id="orders-and-takeaway">Orders and Takeaway</h2>
<p>When you place a takeaway order, we store the information needed to prepare and hand over your order: your name, your mobile number, the items ordered and your chosen pickup time. This information is used to process and confirm your order, prepare it in the kitchen, provide pickup information and maintain a record of the transaction so that we can resolve any issue that may arise. Vanniyan offers takeaway pickup; we do not provide delivery.</p>
<h2 id="table-and-venue-bookings">Table and Venue Bookings</h2>
<p>When you submit a table reservation or a venue booking request, we use the information you provide to check availability, contact you about your request, confirm the booking and manage our daily operations. A submitted request is a request &mdash; it becomes a confirmed booking only once our team confirms it. Venue bookings allow you to reserve our available venue space for your own function or gathering; you organise the event and we provide the venue, subject to our agreed terms.</p>
<h2 id="contact-requests">Contact Requests</h2>
<p>Messages sent through our contact form are used to respond to your enquiry, question, venue request or other restaurant-related communication. Contact messages are visible to the Vanniyan team and are stored so that we can follow up on your request.</p>
<h2 id="google-reviews">Google Reviews</h2>
<p>With your consent to use Google services, the website may display rating and review information about Vanniyan obtained from Google, and may link you to Google to read or write reviews. We do not own the review data displayed on Google, and we do not copy reviews to our own systems beyond what Google&rsquo;s services provide. Google services are subject to Google&rsquo;s own terms and privacy practices. You can read Google&rsquo;s privacy policy at <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">https://policies.google.com/privacy</a>.</p>
<h2 id="analytics-and-marketing">Analytics and Marketing</h2>
<p>Vanniyan may use Google Tag Manager to manage website measurement and marketing technologies. Where enabled, Google Tag Manager may load tags such as Google Analytics to understand which pages are useful, how customers navigate the site and how visitors interact with the menu, bookings and ordering. Where the Meta Pixel is enabled, Vanniyan may use Meta technologies to measure interactions with the website and improve advertising and marketing performance.</p>
<p>These technologies are controlled from the Vanniyan administration panel and can be disabled at any time. Analytics event data never includes payment details, and we do not combine it with sensitive personal information. Where the website&rsquo;s consent tool is enabled, analytics and marketing tags only load after you make an appropriate choice.</p>
<h2 id="cookies">Cookies and Similar Technologies</h2>
<p>We may use cookies and similar technologies for:</p>
<ul>
<li><strong>Essential functionality</strong> &mdash; for example session security, the shopping cart and the booking flow. These are necessary for the website to work.</li>
<li><strong>Analytics</strong> &mdash; to understand how the website is used. These are used only when enabled and, where the consent tool is active, only after you consent.</li>
<li><strong>Marketing</strong> &mdash; to support advertising and marketing measurement through services such as Google Tag Manager and Meta. These are used only when enabled and, where the consent tool is active, only after you consent.</li>
</ul>
<p>You can control cookies through your browser settings and, when the website&rsquo;s consent tool is active, through the privacy banner shown on the website.</p>
<h2 id="third-party-services">Third-Party Services</h2>
<p>The website uses a small number of third-party services to operate:</p>
<ul>
<li><strong>Google Fonts</strong> &mdash; fonts loaded from Google&rsquo;s servers</li>
<li><strong>Google Maps</strong> &mdash; an embedded map and directions links to the restaurant</li>
<li><strong>Google Places</strong> &mdash; rating and review information displayed on the website</li>
<li><strong>Google Tag Manager</strong> &mdash; tag management for analytics and marketing, when enabled</li>
<li><strong>Google Analytics</strong> &mdash; usage measurement, when enabled through Google Tag Manager</li>
<li><strong>Meta</strong> &mdash; the Meta Pixel for marketing measurement, when enabled</li>
<li><strong>Vercel</strong> &mdash; the platform that hosts and serves the website</li>
</ul>
<p>Each of these services processes data according to its own terms and privacy practices.</p>
<h2 id="how-we-use-information">How We Use Information</h2>
<p>We use the information we collect to operate the website, process your orders, bookings and requests, communicate with you about them, maintain records, resolve issues, keep the website secure and, where enabled, understand and improve how the website is used.</p>
<h2 id="how-we-share-information">How We Share Information</h2>
<p>We do not sell personal information. Information may be shared only where necessary to operate the website and provide our services &mdash; for example with the technical service providers listed above &mdash; or where required to comply with legal obligations or to protect the website and our business. These service providers process information on our behalf under their own terms.</p>
<h2 id="data-retention">Data Retention</h2>
<p>We retain information for as long as reasonably necessary for the purposes described in this policy, including operational, legal, accounting and security requirements. When information is no longer needed, it is removed or anonymised.</p>
<h2 id="data-security">Data Security</h2>
<p>We apply reasonable technical and organisational measures to protect the information we hold, including restricting access to the administration functions of the website and using secure hosting. No method of transmission or storage is completely secure, and we cannot guarantee absolute security.</p>
<h2 id="your-choices-and-rights">Your Choices and Rights</h2>
<p>Depending on applicable law, you may have rights regarding access to, correction, deletion or restriction of certain personal information. If you have a privacy-related enquiry, or if you would like us to remove or correct information you provided through the website, please contact us using the details below and we will respond as soon as reasonably possible.</p>
<h2 id="children-privacy">Children&rsquo;s Privacy</h2>
<p>Our website is intended for general audiences and is not directed at children. We do not knowingly collect personal information from children through the website.</p>
<h2 id="external-links">External Links</h2>
<p>The website may link to third-party websites, for example Google, Instagram, Facebook and WhatsApp. We are not responsible for the privacy practices or content of external websites, and we encourage you to review their privacy policies.</p>
<h2 id="changes-to-this-policy">Changes to This Policy</h2>
<p>Vanniyan may update this Privacy Policy from time to time. The latest version will be published on this page with the updated date.</p>
<h2 id="contact-us">Questions About Privacy?</h2>
<p>If you have any questions about this Privacy Policy or how your information is handled, please contact Vanniyan Restaurant through the details shown in the contact section of this website. This policy is a website draft and should be reviewed by a qualified legal professional before being relied upon.</p>
';
    }

    private static function termsConditions(): string
    {
        return '
<h2 id="about-these-terms">About These Terms</h2>
<p>These Terms &amp; Conditions (&ldquo;Terms&rdquo;) apply when you use the Vanniyan Restaurant website and its online services, including the menu, takeaway ordering, table reservations and venue bookings. By using the website, you agree to these Terms. If you do not agree, please do not use the website.</p>
<h2 id="website-use">Website Use</h2>
<p>You agree to use the website lawfully and in good faith. You must not abuse the website, attempt unauthorised access to any part of it, use automated tools to attack or interfere with it, send fraudulent requests, or otherwise engage in malicious activity.</p>
<h2 id="menu-information">Menu Information</h2>
<p>Our menus can change at any time. Prices, dishes and availability may change without notice, and photographs of dishes are illustrative where shown. We try to keep the website accurate, but we do not promise that every menu item will always be available.</p>
<h2 id="prices-and-availability">Prices and Availability</h2>
<p>All prices shown on the website are in Sri Lankan Rupees (LKR) and are subject to the current menu. Availability of items, pickup slots and booking times depends on demand and on restaurant operations at the time of your request.</p>
<h2 id="takeaway-orders">Takeaway Orders</h2>
<p>When you submit a takeaway order through the website, you make a request for the items shown at the prices displayed at the time of submission. The order is subject to confirmation by Vanniyan, and pickup times are subject to availability. Vanniyan provides takeaway pickup; we do not provide delivery.</p>
<h2 id="order-confirmation">Order Confirmation</h2>
<p>An order request is not the same as a confirmed order. An order becomes confirmed only when Vanniyan accepts it. Until then, we may decline or be unable to fulfil a request &mdash; for example because an item is unavailable or the kitchen is at capacity.</p>
<h2 id="pickup">Pickup</h2>
<p>You are responsible for collecting your order within the agreed pickup window. We aim to have orders ready at the selected time, but preparation times may vary with demand. If you cannot collect your order, please contact the restaurant as soon as possible.</p>
<h2 id="table-reservations">Table Reservations</h2>
<p>A submitted table reservation is a request for a table at the date, time and party size you select. Availability is not guaranteed until Vanniyan confirms the reservation. Please provide accurate contact and booking information so that we can confirm your table. Cancellations and no-shows are subject to the restaurant&rsquo;s policy at the time.</p>
<h2 id="venue-bookings">Venue Bookings</h2>
<p>A venue booking allows a customer to reserve Vanniyan&rsquo;s available venue space for their own function or gathering. The customer is responsible for organising their event, subject to Vanniyan&rsquo;s agreed venue terms and requirements. Vanniyan provides the venue; Vanniyan is not the organiser of the customer&rsquo;s event.</p>
<h2 id="venue-booking-confirmation">Venue Booking Confirmation</h2>
<p>Venue requests follow this workflow:</p>
<ol>
<li>Request submitted through the website</li>
<li>Vanniyan reviews the request</li>
<li>The customer is contacted</li>
<li>The venue booking is confirmed</li>
</ol>
<p>A request is not a confirmed booking. It becomes confirmed only when Vanniyan confirms it after review.</p>
<h2 id="venue-capacity-and-services">Venue Capacity and Services</h2>
<p>Venue bookings are subject to capacity, date, time, availability and venue rules. Any additional services &mdash; for example catering, tables and chairs, decoration, stage or sound &mdash; are subject to availability and to the specific agreement for your event. Services are not promised automatically by submitting a request.</p>
<h2 id="offers-and-promotions">Offers and Promotions</h2>
<p>Offers shown on the website may have conditions, validity periods and limits, and may be changed or withdrawn at any time. Not every offer applies to every service. The terms shown with each offer apply.</p>
<h2 id="physical-loyalty-card">Physical Loyalty Card</h2>
<p>Vanniyan&rsquo;s physical loyalty card rewards repeat visits: the 5th visit earns a free drink, and the 10th visit earns a Rs. 1,000 food coupon. Eligibility is subject to Vanniyan&rsquo;s loyalty card terms. The loyalty card is a physical card; there is no digital points system on this website.</p>
<h2 id="customer-responsibilities">Customer Responsibilities</h2>
<p>You are responsible for providing accurate information, including your name, phone number, booking details and order details. If the information you provide is inaccurate, Vanniyan may be unable to process your request or confirm your booking.</p>
<h2 id="content-and-intellectual-property">Content and Intellectual Property</h2>
<p>The Vanniyan name and logo, the design of the website, our original photographs, cultural artwork, written content and software are owned by or licensed to Vanniyan and are protected by applicable intellectual property laws. You may not reproduce them without permission. Content belonging to third parties &mdash; for example Google reviews &mdash; remains owned by its respective owners.</p>
<h2 id="third-party-services">Third-Party Services</h2>
<p>The website uses third-party services, including Google and Meta services, to operate and measure the website. Those services are governed by their own terms and privacy practices.</p>
<h2 id="website-availability">Website Availability</h2>
<p>We aim to keep the website available and reliable, but we cannot guarantee uninterrupted access at all times. We may suspend or change parts of the website for maintenance or other reasons.</p>
<h2 id="limitation-of-liability">Limitation of Liability</h2>
<p>To the fullest extent permitted by law, Vanniyan&rsquo;s liability in connection with the website and the services described in these Terms is limited to the amount paid by you for the relevant service, or to what is required by law. Nothing in these Terms limits liability that cannot be limited by law.</p>
<h2 id="changes-to-these-terms">Changes to These Terms</h2>
<p>Vanniyan may update these Terms from time to time. The latest version will be published on this page with the updated date, and continued use of the website after an update means you accept the updated Terms.</p>
<h2 id="governing-law">Governing Law</h2>
<p>These Terms are governed by the laws of the jurisdiction shown in the governing law setting for this website.</p>
<h2 id="terms-contact">Contact</h2>
<p>If you have any questions about these Terms, please contact Vanniyan Restaurant through the details shown in the contact section of this website. These Terms are a website draft and should be reviewed by a qualified legal professional before being relied upon.</p>
';
    }
};