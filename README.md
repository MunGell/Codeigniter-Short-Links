Bitly-like script for Codeigniter (Shortlinker)
===============================================

Description
-----------

This library adds short links function to your Codeigniter project.

More information about Codeigniter libraries available on [Codeigniter Documentation](http://codeigniter.com/user_guide).

Usage
-----

First of all, create a `linker` table in your database. Take a look on `db` directory.

There are two ways to load library to Codeigniter. Automatically within **./config/autoload.php** or directly in your controller by adding this line:  

	$this->load->library('shortlinker');

Generate a shorten link:

	$short_link = $this->shortlinker->getLink('http://ya.ru');
	
Alternatively, you can define you custom hash for the link (i.e. `t.co/custom_value`):

	$short_link = $this->shortlinker->getLink('http://ya.ru', 'custom_value');

In addition there is sample "receiver" controller in the repository. Please refer to controller directory.

Feedback
--------

Any other questions are welcome [here](https://github.com/MunGell/Codeigniter-Short-Links/issues).
	
Warning
--------

This library was extracted from one of my non-Codeigniter projects and was not tested in full in Codeigniter environment.

Please, report to the issue tracker for any bugs you find in the library. Thank you. 