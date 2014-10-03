activeCollab PHP API Library
============================

This library is intended to simplify the process of communicating with an activeCollab
install through the activeCollab API using PHP.  This project is definitely in pre-alpha
stages and some major core changes may happen at any time.  That being said, please
feel free to try it out on your own test projects in activeCollab.

Getting Started
---------------

Whenever I use a new (to me) product, it is really helpful when there are some getting
started material as well as some examples.  I am trying to document the code through
code comments and will eventually develop better documentation.  If you know of a good
product that mines PHP code comments and generates nice PHP documentation pages, please
let me know as that will greatly help me!

Right now, the best way to get started is to look at my tests inside the `tests` folder.
These tests will show you through code how to begin using the API.

### API Structure

The API is split up into a number of logical folders to help with separation of
concerns.  Here are what each of the folders contain:

* `examples` - A folder containing outdated information that will eventually be deleted or improved to include legitamate examples.
* `exceptions` - Class definitions for any custom PHP Exceptions.
* `helpers` - Any general purpose helper classes.
* `lib` - Any external libraries that this library uses.
* `models` - The PHP classes that do the actual communication to the activeCollab install through the API.
* `objects` - The classes that you will most likely be using throughout your code.  These correspond to the various Models.
* `tests` - A set of tests that help with debugging the library.
* `ActivecollabAPI.class.php` - The main file you will include in your program.

### 
