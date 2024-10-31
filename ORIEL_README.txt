=== Oriel Lite ===

Author: Oriel Ventures Limited
Author URL: https://oriel.io/
Version: 1.1
Tags: adblock, analytics
Requires at least: 4.4
Tested up to: 4.7
License: GPLv2
License URL: https://www.gnu.org/licenses/gpl-2.0.html

Oriel enables your website to collect ad block analytics and to communicate with your ad blocking audience.

== Description ==

Oriel Lite is a Wordpress plugin which provides you with tools to precisely measure the impact and level of ad-blocking on your website; offers you insights about your ad-blocking visitors including how many as a total number and percentage of visitors, what countries they come from, what device types they use, be it, mobile, tablet or desktop, along with details of operating systems and browsers used.

Oriel WordPress plugin has been rigorously tested, including a full external security audit, against all common attacks e.g. SQL injections, XSS, CSRF, path traversal, remote file inclusion and more. All communications made between the the plugin and Oriel are all done through HTTPS encrypted connections so no confidential or sensitive information is exposed.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. After activation, access Settings/Oriel section and fill in the API key provided above.


== Frequently Asked Questions ==

HOW ACCURATE ARE ORIEL ANALYTICS?
We continuously compare and benchmark our analytics against industry leaders and are currently seeing between 0.2 - 0.6% deviation in numbers between analytic tools such as Adobe and Google Analytics. Furthermore, because our analytics, unlike Google and Adobe analytics, is not blockable by adblocking software we are in many instances able to provide more detailed information and statistics on such visitors than would normally be available.


WILL ORIEL WORDPRESS PLUGIN IMPACT MY WEBSITE PERFORMANCE?
No. The plugin is not running CPU intensive operations and memory load is usually less than 1 MB. The Oriel WordPress plugin does not execute heavy SQL queries, additionally and because the queries results do not change regularly these are cached by the database server so no disk IO increase is expected. On average, the performance impact on page latency is around 5 milliseconds.

ARE ORIEL’S ANALYTICS LIVE?
Oriel’s analytics update themselves every 10 seconds.

HOW CAN I EXPORT THE DATA DELIVERED BY ORIEL’S ANALYTICS?
There is a button next to each of the analytics which gives options to export the analytics to the publisher as a CSV.

DOES ORIEL CONNECT TO A 3RD PARTY SERVICE?
Yes. The plugin connects to the Oriel Backend API (https://gw.oriel.io/api) to fetch the client side javascript file
which is then run in the browser. This script runs the client-side ad-block detection and reports the ad-block status
to the Oriel Servers for ad-block analytics processing, made available in the Oriel Console.
The JS file is also cached for improved performance.

