# ep-3 Bookingsystem

The ep-3 Bookingsystem is an open source (MIT licensed) web application to enable users to check and book free places of
an arbitrary facility easily online via one huge calendar.

It was initially developed to enable booking free squares of a covered court for a tennis club, improved along some
versions, tried to offer commercially as a SaaS - and finally released as open source software.

Among its primary features are extensive customization capabilities (thus making it interesting even outside the tennis
branch), multilingualism (currently shipped with english and german), an interactive, easy-to-use calendar, an
easy-to-use and easy-to-understand backend, a consistent and clear visual design and a fully responsive layout (thus
looking nice on mobile devices as well).

More features may be explored via our website (http://bs.hbsys.de/) or simply by downloading and trying the system
yourself.

## Documentation and installation

Documentation and installation instructions can be found in the following directory:

```
data/docs/
```

## Architecture

The system is based on the well-known LAMP stack (Linux, Apache 2, MySQL 5+, PHP 5.6+) and the powerful
[Zend Framework 2](http://framework.zend.com/) (2.5).

It is compatible with PHP version up to and including 7.4. We are currently working on an upgrade of the underlying
Zend Framework to make it compatible with PHP 8.0.

Dependencies are managed with [composer](https://getcomposer.org/).

The source code is version controlled with [Git](http://git-scm.com/) and hosted at [GitHub](https://github.com/).

The link to the GitHub repository is

```
https://github.com/tkrebs/ep3-bs
```

where you can find stable and (latest) development releases.

## Version

The current version is 1.15.1 from September, 2022.

Version 1.15.1
Handling of conflicts 

Version 1.15.0
Long time reservations for Dabas: one time booking, repeated payment

Version 1.14.1
Autocomplete bug

Version 1.14.0
Backand/config/prices big rewamp

Version 1.13.1
Small responsitivity issues

Version 1.13.0
Correct some responsivity issues
Added billing status to bookings list
Change the handling of billing sums

Version 1.12.0
Advanced statistics and search on backend/users

Version 1.11.1
Advanced statistics on backend/bookings

Version 1.11.0
Improve advanced search on backend/bookings

Version 1.10.3
Datepicker js bug

Version 1.10.2
Improvement of bookings list

Version 1.10.1
Save squaretype bugs

Version 1.10.0
Big update on i18n date handling, user and booking listings, hungarian translation bugs

Version 1.9.2
Bugfix

Version 1.9.1
Bugfix

Version 1.9.0
Migrate Zend framework to Laminas

Version 1.8.3
Small fixes

Version 1.8.2
Cookie consent popup

Version 1.8.1
Bugfixes to 1.8.0

Version 1.8.0 corrects bug by language selector. Introducing new function court-types, to be able to handle unlimited number of courts. (Géza Várszegi)

Version 1.7.0 provides compatibility with PHP 7.4 by overriding and fixing some of the Zend Framework 2 components.

Version 1.6.4 introduced some features required during the COVID-19 pandemic, including limits to active concurrent bookings and minimum booking ranges. It also includes minor bug fixes and improvements.

Version 1.6.3 introduced some GDPR compliance based changes and requested features.

Version 1.6.2 changed the configuration behaviour and requires some manual changes (see data/docs/update.txt). Otherwise, the update will not work.

Version 1.6 introduced some requested features and fixed quite some bugs. It also introduced better support for custom translations and modules.

Version 1.5 introduced some requested features (billing administration, custom billing statuses and colors) and fixed some bugs.

Version 1.4 introduced some requested features and the latest third party libraries and frameworks.

## Bug reports, feature requests, ideas ...

We use the GitHub Issue Tracker for such things:

https://github.com/tkrebs/ep3-bs/issues
