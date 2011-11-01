This is a demo app for [Phresnel](https://github.com/lobid/Phresnel), an incomplete, experimental PHP implementation of the [Fresnel Display Vocabulary for RDF](http://www.w3.org/2005/04/fresnel-info/) that includes a browser based editor for RDF data.

You can try it out here:

    http://literarymachine.net/PhresnelApp/organisation
    http://literarymachine.net/PhresnelApp/person
    http://literarymachine.net/PhresnelApp/document

To install it locally, clone this git repository recursively into a directory available to your webserver:

    $ git clone --recursive https://github.com/lobid/PhresnelApp.git

Phresnel uses [LibRDF](https://github.com/literarymachine/LibRDF), which is a simple OO rapper around the PHP5 language bindings for the Redland RDF library and thus needs these bindings to be installed. This works out-of-the-box for me on Ubuntu 10.10, later versions of the bindings unfortunately lead to yet to be traced segfaults:

    $ sudo aptitude install php5-librdf

The demo app and all necessary libraries should now be installed. Next, create the config file in the conf/ directory:

    $ cd PhresnelApp/
    $ cp conf/conf.ttl.sample conf/conf.ttl

Modify the phresnel:lenses property in conf.ttl to point to the file containing the Fresnel lens definitions (use an absoulte path). The demo uses flat files to store data, which will be located in the db directory of the app. Create this directory and ensure that the webserver can write to it:

    $ mkdir db
    $ chmod a+w db

Point your browser to one of

    http://localhost/path/to/PhresnelApp/organisation
    http://localhost/path/to/PhresnelApp/person
    http://localhost/path/to/PhresnelApp/document

and click on the link to add a new description. Links e.g. from persons to organisations can only be added when an organisation already exists.

Play with conf/lenses.ttl to add or remove properties. Be warned: if you read the [Fresnel documentation](http://www.w3.org/2005/04/fresnel-info/manual/), don't expect *anything* to work in Phresnel. This is still an experiment.
