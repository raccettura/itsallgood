# What it's for #

It's All Good is a simple service monitor framework to make it easy to monitor various services on a server.

At its core it's a light framework for checking various aspects of a server and deciding if things are operating within defined parameters or not.  So far it has "out of the box" support for:
  * **CPU Load** - As simple as it sounds.  Check that your CPU load doesn't exceed a threshold you define.
  * **Disk Usage** - Sets off an alarm when your server is running low on disk space.
  * **SMTP Ping** - This makes a connection to your SMTP server to check that it's online and operational.
  * **MySQL Check** - Checks to see if it can make a successful connection to a MySQL server.
  * **HTTP(s) Check** - This can connect to a HTTP or HTTPS server and check that it connected successfully as well as check that for a condition on the page.  This is handy to make sure a web app is up and running or that your SSL cert isn't expired.


At it's core it's just a framework, so adding other checks are relatively easy.  There's lots more I want to include (memory, disk IO, process monitor for example).  It's designed to monitor the host, not a series of servers (though technically doable).  This isn't <a href='http://www.nagios.org'>Nagios</a>, it's a way to get a quick glance at your key services on a host.

On its own it doesn't send any notifications.  It's designed to be combined with the keyword monitoring feature of services like [Pingdom](http://www.pingdom.com/), [Monitis](http://portal.monitis.com/), [Host-Tracker](http://host-tracker.com/), [SiteUptime](http://www.siteuptime.com/), or [Howsthe.com](http://www.howsthe.com/) among others.  This way you not only check services, but the server itself.  If anything fails, you will be notified by your monitoring provider.

It's All Good also has a UI for an admin to view which can give you the status and a basic rundown of its polling data.  It's also designed to so that it's pretty easy to read on mobile devices like the iPhone, making it a great dashboard for on the go.

Lastly it's designed to be pretty light and quick, so unless you are monitoring a ton of things on your server, it shouldn't have any real overhead.

So far I've only implemented real support for the checks for Linux.  I suspect most will work on BSD, and Darwin (though not all).  Windows still needs some help.  Patches are welcome.  I'd also like to support things like IP whitelist/blacklists (automated via RSS fetches), and lots of modules to extend what it can keep track of.

# Requirements #
  * PHP 5 or later (5.2+ strongly recommended)
  * Linux/BSD/Solaris webserver (Windows currently isn't supported).

# Setup #

Here's the basics of how to install
  * Download and extract the tarball on your server somewhere web accessible.
  * Edit `config.php.default` as desired, you'll need to uncomment the blocks you want.  See `docs/readme` for a more detailed explanation.
  * Rename `config.php.default` to `config.php`.
  * Browse to the directory with your web browser.

That should be it.

# Credit #

By [Robert Accettura](http://robert.accettura.com).

# License #
GPL v2