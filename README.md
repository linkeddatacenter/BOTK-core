# BOTK\Core
[![Build Status](https://img.shields.io/travis/linkeddatacenter/BOTK-core.svg?style=flat-square)](http://travis-ci.org/linkeddatacenter/BOTK-core)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/linkeddatacenter/BOTK-core.svg?style=flat-square)](https://scrutinizer-ci.com/g/linkeddatacenter/BOTK-core)
[![Latest Version](https://img.shields.io/packagist/v/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![Total Downloads](https://img.shields.io/packagist/dt/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![License](https://img.shields.io/packagist/l/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)

Super lightweight classes for developing cool RESTful APIs. Based on Respect/Rest package.

 * Very thin and lightweight.
 * Don't try to change PHP, small learning curve.
 * Completely RESTful, the right way to build apps.

This is a BOTK package. Please refer to http://ontology.it/tools/botk for more info
about BOTK project.

## quickstart

The package is available on [Packagist](https://packagist.org/packages/botk/core).
You can install it using [Composer](http://getcomposer.org).

```bash
composer require botk/core
```

Some code examples in samples directory.


# Core package documentation


# Installation

This package require [composer](http://getcomposer.org/).

Add following dependance to **composer.json** file in your project root:

```
    {
        "require": {
            "botk/core": "*"
        }
    }
```

# Overview

Core package libraries are designed to implement [RESTful
          Web APIs](http://en.wikipedia.org/wiki/Restful#RESTful_web_APIs) according with the [RESTful
          constraints](http://en.wikipedia.org/wiki/Restful#Constraints) and taking into account the best practice in APIs design (for instance see [Apigee
          Api Best Practice books](http://apigee.com/about/api-best-practices) ).

You can use Core Package to develop:

*   **web applications** : just PHP [CGI 1.1 interface](http://www.ietf.org/rfc/rfc3875)scripts.
*   **RESTful APIs**: web applications that accept the RESTfull architectural constraints.
*   **BOful APIs**: RESTFul APIs using Semantic Web Architecture according [BOTK
                specifications](../overview/).

Core package is an extension of [Respect\Rest](http://github.com/Respect/Rest) library and it is
        designed for having a minimum memory footprint and minimum overhead on servers.

The following code snippet is a simple RESTful API that represents the string '"Hello world". It manages [HTTP
          Content negotiation](#contentNegotiation) supporting: application/json, application/xml, text/html, application/x-php,
        text/x-php, text/plain. It also [manages errors](#errorManagement) according last available [http_problem
          proposal](http://tools.ietf.org/html/draft-nottingham-http-problem-04) RFC, rendering _http problem_ as:&nbsp; application/api-problem+json,
        application/api-problem+xml, text/html, application/x-php, text/x-php, text/plain.

```
    require '../vendor/autoload.php';
    use BOTK\Core\EndPoint, BOTK\Core\EndPointFactory, BOTK\Core\ErrorManager,
        BOTK\Core\Representations\Standard;

    class MyEndPoint extendsEndpoint {
        protected function setRoutes() { 
            $this->get('/', 'Hello world')->accept(Standard::renderers()); 
        }
    }

    try {                                                      
        echoEndpointFactory::make('MyEndPoint')->run();
    } catch ( Exception $e) {
        echo ErrorManager::getInstance()->render($e); 
    }
```

Core package is designed around the endpoint concept:

> An endpoint&nbsp; is a Web service that provides server side [RESTful
>             web API](http://en.wikipedia.org/wiki/Representational_state_transfer#RESTful_web_APIs). An endpoint can be described with [URI templates](http://tools.ietf.org/html/rfc6570)
>           and implemented in a [CGI](http://www.ietf.org/rfc/rfc3875) script.
> 
> *BOTK definition of an endpoint*

Core package implements endpoints inEndpoint[ class](#EndPoint).

The Core package is a killer one, i.e. it is a really sophisticated package which provides a powerful way to
		implement web services. Even if the Core package is focused to [server-side
		  APIs](http://en.wikipedia.org/wiki/Web_API#Server-side), with it you can do nearly all types of web application you ever dreamed about. The price you have to
		pay is to accept complexity, because Core package major drawback is that it is not easy to understand and to use
		at the beginning. 

In other words: with Core Package you either shoot yourself in the foot the first time and never use it again or
love it for the rest of your life because of its power.

Core package follows the BOTK "non framework" philosophy, that is that grants you the freedom to adopt or
        reject BOTK guidelines and/or best practices. Find your way to use Core Package!

## Architecture

Like in many PHP frameworks, Core package adopts [Model–view–controller](http://en.wikipedia.org/wiki/Model_view_controller) (MVC)
design pattern adapting it to RESTful architecture.

Classical MVC design pattern separates the application into three parts:

*   The **Model** models your business objects, the "things" in your application, wrapping updata handling and logic. 
*   The **View** manages the application output. 
*   The **Controller** manages the application flow manipulating data from the Model and driving
              views.

MVC has been widely embraced by Web developers even though it was designed for desktop applications. MVC
        doesn't really maps perfectly well on the Web, because it doesn't model and expose resources in a convenient way
        (actions are methods on controllers exposed as resources rather than methods on resources themselves). Core
        package adapts of the classic MVC to web architecture, and in particular to RESTful APIs architecture:

*   the "C" part of MVC (i.e. Controller) is realized with a set of classes to control various aspect of HTTP
              protocol : 
*   *   [_EndPoint_](#EndPoint) manages [Uri
                        templates](http://tools.ietf.org/html/rfc6570) and [HTTP request
                        headers](http://en.wikipedia.org/wiki/List_of_HTTP_header_fields) throw routes to a resource controller method and routines. It controls [HTTP
                        content negotiation](http://en.wikipedia.org/wiki/Content_negotiation), [HTTP cache
                        management](http://www.w3.org/Protocols/rfc2616/rfc2616-sec13.html) and out-of band processes like logging and billing.
    *   [_Controller_](#Controller) controls the HTTP protocol for a single resource. It is
                      responsible for **resource model** instantiation, for **resource state transitions**.
*   The "V" part of MVC (i.e. View) is realized by [renderers](#renderer) that produce resource
              state representation in HTTP response managing the production of a proper&nbsp; response content type header
              (normally calling [serializers](#serializer)). 
*   The "M" part (i.e. Model) are implemented as any PHP data structure (scalar, array, streams or classes) and
              refers to Web Resource.Core package frees you to chose what paradigm use for&nbsp; **Resource Modelling**:
              you can follow a pure object oriented approach and putting data (i.e. _properties_) and functions (_methods_)
              together inside the model itself, or you can follow the web architecture keeping separate data (_resource
                state_) from functions (_resource manager_). Core package supports the restoring of a Resource
              State from its representations through [parsers](#parser) that read a specific resource state
              representations in HTTP request and translate them in a PHP data model (that is a scalar or an associative
              array of 'property' => 'value', or a PHP parsable program)..

Parsers and renderers are defined in [Content Negotiation Policies](contentNegotiation) that group
        all **resource state representation management** functions.

Core package enforces a strict [separation for
          concerns](http://en.wikipedia.org/wiki/Separation_of_concerns) between Applications,Endpoints, Controllers and Content Negotiation Policies:

![Core as MVC implementation](doc/images_file/png_1.png) 

This approach allows you to write reusable endpoints as in this RESTful web service example:


    require '../vendor/autoload.php';

    use BOTK\Core\EndpointFactory,          // Create endpoint
        BOTK\Core\EndPoint,                 // provides the web service runtime
        BOTK\Core\Controller,               // controls http protocol
        BOTK\Core\ErrorManager,             // Controls errors
        BOTK\Core\Representations\Standard, // provides many resource representations
        BOTK\Core\WebLink,                  // add hypermedia capability
        BOTK\Core\Caching;                  // manage HTTP caching

    /* This class implements MVC Model */
    class Greeting
    {
        public $greeting = 'Hello', $to = '', $by = 'http://www.e-artspace.com/';  
        public function __construct($to='World') { $this->to=$to;}
        public function __toString() {return "$this->greeting $this->to";}
    }

    /* This class implements MVC View */
    class GreetingRepresentation extends Standard {}

    /* This class implements MVC Controller for Resource*/
    class HelloworldController extends Controller
    {
        public function get($to='World')
        {
            return $this->stateTransfer(
                $hello = new Greeting($to),
                WebLink::factory($hello->by)->rel('next')
            );
        }
    }

    /* This class implements MVC Controller for endpoint*/
    class Helloworld extendsEndpoint
    {
        protected function setRoutes()
        {
            $this->get('/*', new HelloworldController)
                ->accept(GreetingRepresentation::renderers())
                ->through($this->representationCachingProcessor(Caching::SHORT));
        }
    }

    //uncomment above to use your css:
    //Standard::$htmlMetadata = 'http://www.w3.org/StyleSheets/Core/parser.css?family=6&doc=XML';
    try {                                                      
        echoEndpointFactory::make('Helloworld')->run();
    } catch ( Exception $e) {
        echo ErrorManager::getInstance()->render($e); 
    }


This simple implementation provides json, html, xml, PHP and text representation of a web resource, whose
        state is modeled as a simple PHP object. It advertises the next resource you can visit after getting its
        representation in any format. The web service informs the client that it can safely cache it for at least 30
        sec.(see [Caching chapter](#httpCaching) for more info). Note that default ` stateTransfer() `
        processor provided by Core\Controller implements hyperlinks as required by RESTfull architecture. And all of
        this for a small price of about 300KB of memory footprint, using uncompressed PHP code! That's will be, more or
        less, the overhead of using Core package on your specific application code.&nbsp;


## Data Flow

The data flow is managed in four phases

1.  HTTP request parsing 
2.  endpoint workflow 
3.  resource controller workflow 
4.  HTTP response emission

### Request parsing

Request parsing splits an HTTP request in pieces of manageable information.Core package rely on PHP way to
          parse HTTP request that in turn rely on [CGI](http://en.wikipedia.org/wiki/Common_Gateway_Interface)
          (Common Gateway Interface) to manage HTTP protocol. HTTP Request information are contained in PHP superglobal
          variables [$_SERVER](http://www.php.net/manual/en/reserved.variables.server.php), [$_FILES](http://www.php.net/manual/en/reserved.variables.files.php),
          [$_GET](http://www.php.net/manual/en/reserved.variables.get.php), [_POST](http://www.php.net/manual/en/reserved.variables.post.php).
          Request body is available as php://input or STDIN stream. Because of stateless nature of RESTful web
          applications** you are discouraged to use cookies and session variables**. In core Package ther
          is no support for such HTTP protocol parts. Here is a summary of Core supported HTTP request parts:

![Http request parsing](doc/images_file/png_3.png)

BOTK [Context package](../context/) add some facility and helpers to access to PHP HTTP
          variables together with configuration and environment information.

### EndPoint workflow

TheEndpoint workflow objective is to route an HTTP request to a function that manage it. The role of
          endpoint consists in:

*   selecting the appropriate action controller and route to it the client request to one using one of the
                HTTP methods (get, put, post, etc.) exposed by a Controller. 
*   to manage content negotiation 
*   to manage HTTP caching of resource state final representation (i.e. checks if resource representation is
                changed) 
*   to manage other out-of-band business logic like logging and billing

Here a simplified picture of internal dataflow for aEndpoint class:

![EndPoin tDataflow](doc/images_file/png_4.png)

### Resource Controller workflow

The controller workflow objective is to provide an implementation for HTTP method to serve an client request.
        The responsibility of the Controller is:

*   to validate request 
*   to instantiate resources models 
*   to apply business logic 
*   to define next steps in application workflow 
*   to manage caching at Resource Model level (i.e. checks if model is changed)

Here a simplified picture of internal dataflow for a controller class:

![Controller dataflow](doc/images_file/png_5.png)

### HTTP Response emission

Core package rely on PHP to emit response request. All you echo in application will be route
        to response body bypass content management.

![Http request parsing](doc/images_file/png_6.png)

BOTK Context package add some facility and helpers to access to PHP HTTP variables together with configuration
        and environment information.


## How Core package helps your APIs to be RESTfull

According to[ Roy T. Fielding and
            Richard Taylor dissertation](http://www.ics.uci.edu/%7Etaylor/documents/2002-REST-TOIT.pdf), the REST architectural style is derived from six constraints applied to the
          architecture. This chapter analyze such constraints and give you some hints about how using Core Package and
          PHP to be RESTful compliant.

> A uniform interface separates clients from servers...
> 
> *Client–server*

Use HTTP 1.1 as the only application protocol for client server interface.The Core package concerns the
          implementation of server side and supports uri templates.

> Each request from any client contains all of the information necessary to service the request, and session
>             state is held in the client...
> 
> *Stateless*

Core package never use $_SESSION and $_COOKIES superglobals . To enforce this style guide consider using [Context Package](../context/).

> As on the World Wide Web, clients can cache renderers...
> 
> *Cacheable*

Endpoints and Controller class provide a set of facilities to manage HTTP cache headers. See [HTTP
            Caching chapter](#httpCaching) for more info.

> A client cannot ordinarily tell whether it is connected directly to the end server, or to an intermediary
>             along the way...
> 
> *Layered system*

Core Web services can be be called from any CGI capable web server (not only Apache) using preferred
          technology (fastCGI, mod_php, cgi, etc). Core package doesn't&nbsp; use proxy related headers to drive
          actions.

> Servers can temporarily extend or customize the functionality of a client by the transfer of executable
>             code...
> 
> *Code on demand*

The content negotiation standard policies support "text/x-php" that allow clients to receive the response
          body as an executable PHP program. Beside this, in Standard Content Negotiation Policy, any textual data
          representation (both in tex/plain and in html) is expressed as PHP interpretable code) See [Content
            Negotiation Chapter](#contentNegotiation) for for more info.

> The uniform interface between clients and servers, simplifies and decouples the architecture, which
>             enables each part to evolve independently...
> 
> *Uniform interface*

Core package does not impose a uniform content type for response but supports different resource
          representation (json, xml, html, etc.) through content negotiation .Both for requests and responses.

Beside this, **Roy T. Fielding stressed that [REST
          APIs must be hypertext driven](http://roy.gbiv.com/untangled/2008/rest-apis-must-be-hypertext-driven)**. Unfortunately many formats typically used to serialize the
          status odf web resources (i.e, json, plain xml, plain text) do not support hyperlinks and metadata. **BOTK
          suggests RDF as preferred hypermedia data model**. The BOTK support to [RDF is
          available as a separate package](../rdf/).&nbsp; By the way, [Core\Controller](#Controller) use Web
          Links in HTTP header to provide an Uniform hypermedia&nbsp; interface between client and server for all non
          hypermedial formats .


## Why to be RESTfull

Conforming to the REST architectural-style, enables any kind of distributed hypermedia system to have
        desirable emergent properties, such as performance, scalability, simplicity, modifiability, visibility,
        portability, and reliability.

Please note that if a web service service violates any of the required constraints, it cannot be considered
        RESTful.

# EndPoint Class

` Core\EndPoint ` is the the entry point of web services resource processing.

Endpoint is able to manage simplified [URI template](http://en.wikipedia.org/wiki/URL_Template) but
        is not able to route using [URI query string](http://en.wikipedia.org/wiki/Query_string) nor [URI
          Fragment](http://en.wikipedia.org/wiki/Fragment_identifier).Endpoint it is a specialization of `[Respect\Rest\Router]()` Class.

Best practice is to route HTTP request methods to one or more Action Controllers, but anEndpoint it is able to
        route directly to a closure function or even to a string:

    final class HelloWorldEndPoint extendsEndpoint
    {
      protected function setRoutes()
      {
        // route HTTP GET method to a string
        $this ->get('/helloworld', 'Hello World');

        // route HTTP GET method  to a closure
        $this ->get('/sayHello', function(){ return  'Hello World';});

        // route HTTP GET method to a closure (with template)
        $this ->get('/hi/*', function($x='hello'){ return  "Hello $x";});

        //route to a controller class ( that will be instanced runtime )
        $this ->any('/hello/*', 'MyHelloController'); // be sure to implements all method if use any.

        //Best practice: route to your instanced controller
        $this ->get('/hello/*', new MyHelloController);
      }
    }

` Core\EndPoint` class is also able to mount other existing endpoints. It is easy to build complex
        RESTful APIs composing a set of smaller and reusable components. Endpoint class is able to allocate run time
        only the needed endpoint. This feature helps to minimize the run-time memory footprint because it avoids the
        loading and parsing of unnecessary code:

    echo EndpointFactory::make()
          ->mountEndPoint('users', 'UsersEndPoint')
          ->mountEndPoint('hello', 'HelloWorldEndPoint')
          ->run();      

Endpoint class add some value to Respect\Rest\Router class:

*   can mount existing endpoints as routes allocating run-time just when needed, this allow a memory
              optimization and a better code cleanness. 
*   can detects virtualhost from CGI variables auto-detecting the most common url rewriting done by web
              servers.

      In any case you can use all Respect\Rest features.See [Respect\Rest](http://github.com/Respect/Rest)
      documentation for more info.


# Controller Class

The Core\Controller is a class that implement actions for standard [HTTP
          request methods](http://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol#Request_methods) (get, put, post,delete, options,head) . Note that Controller is completely decoupled from
        routing and content negotiation.

The best practice is that a controller should take no assumption about how to read/write resource state
        letting this job to content management policies. Core\Controller it is the closest thing to the C in MVC, BOTK
        controller takes care to instantiate a Model decoupling HTTP flow management from data business logic.

This is a short snippet of a simple Controller implementation:

    class MyRESTfulController extends Controller
    {
        public function get()
        {
            $obj= new ResourceModel;
            $this->setState( $obj, Representations\Standard::restorers());

            return $this->stateTransfer(
                Caching::resouce($obj, Caching::CONSERVATIVE),
                WebLink::factory('HTTP://myDomain/')->rel('copyright')
            );
        }
    }
      In any case controller can bypass hypermedial state transfer processing returning the data: 
    class QuickController extends Controller
    {
        public function get()
        {
            return new MyResourceModel;
        }
    }

Or even bypass all best practice and just directly output content body:

    class QuickAndDirtyController extends Controller
    {
        public function get()
        {
            echo 'hello world';
        }
    }
# Data modelling

Core package doesn't impose any constraint on resource data models. In principle you can use a scalar, an
        array or anything that you can model as PHP data:

    class WorldModel
    {
      public $helloTo = 'everybody';
    }

Note that&nbsp; you can decide to follow a pure object oriented approach and putting data (i.e. _properties_)
        and functions (_methods_) together inside the resource model itself, or you can follow the web
        architecture keeping separate data (_resource state_) from functions (_resource manager_).

By the way in web architecture the resource is something supposed to be serialized in one or more formats, so
        your data model should be mandatory recognized by representation renderers. More, in RESTful architecture a
        model should derive its status from a content provided by client (POST, Put and PATCH methods), so happens that
        external representations sometime need also to be parsed and restore a Resource model.

For these reasons in Core Package Data models are bonded to Content Management policies that provides all
        renderers and parsers. Note that in content management policy the resource state representation renders are
        completely disjointed from the resource state representation parsers. Normally parsers supports a subset of
        provided renderers.

Core package provides a set of ready to use content management policy to support the rending an the parsing
        main PHP data structure and in particular:

<dl><dt>Standard content management policy</dt><dd> renderers any PHP serializabile data structure in json, xml,html, PHP serialized, and plain text. It
          provides restorers that automatically build StdClass, scalar or Array for application/json,
          application/core+xml and serialized PHP content type. </dd><dt>Error content management policy</dt><dd> renderers Core\Models\Error data structure in json, xml,html, PHP serialized, and plain text. It provides
          no restorers. </dd></dl>

# Content Negotiation

[HTTP Content negotiation](http://en.wikipedia.org/wiki/Content_negotiation) is managed as a
        part of dynamic, non binding contract between a client and a server to exchange a Resource Representation State.
        In Core package, content negotiation is the result of a cooperation between:

*   a_ content negotiation policy_ issued by server 
*   an _accept header_ submitted by client 
*   a set of _renderer_ functions that build an HTTP response rendering a resource in a specific
              content type using a serializer 
*   a set of _serializer_ functions that transform a resource data structure in a utf8 encoded strings

*   a _Content-Type header_ provided by client as request body 
*   a _resource Model_
*   __a set of _restorer_ function that restore the Resource Model state using the data
              provided by client, and a proper selected parser.
*   __a set of _parser_ functions that read the resource state provided by clients according a
              specific format

## Content negotiation actors

### Renderers

A renderer is a functions that detects, selects, calls serializers and sends to client proper HTTP `          Content-Type ` header. A renderer is a PHP function that expose a standard interface accepting a PHP
        data structure as the only parameter. Its role is to manage Content-Type header in response and call a
        serializer with the right parameters. Renderers is also responsible to look inside input data to see if exposes
        specialized [serializers](#serializer). Renderers functions are normally (but non mandatory)
        implemented as static methods in content negotiation policies. For example:

**By default renderers should expose only public variables in data model**.

Renderers should provide HTTP weblinks to alternate formats available for the same resource; this is
        automatically performed by the function ` setContentType()` provided by `          AbstractContentNegotiationPolicy` class:

### Serializers

A serializer is an helper function that translate a data model into a utf8 string according with a specific
        format.

[Representations\Standard](#Standard) provides default serializers to all those objects that do
        not expose their own custom serializers and to non object data structures (arrays and scalars).

### Parsers

A parser is a function that accept a string that is supposed to contain a serialization of a resource state in
        a specific format and returns a resource state as a PHP associative array structure that contains property value
        pair or a string as produced from a [`            var_export()`](http://php.net/manual/en/function.var-export.php) PHP standard function.

### Content negotiation policies

Content negotiation policies are libraries to provide renderes, serializer, restorers and and parsers**          for a specific set of Resource Model Types**. Content negotiation policies are managed by [accept
          process](#accepting) , by Status [restore process](#restoring) , by [error
          management](#ErrroManager) and by who need to read an HTTP content body request or to write an HTTP content body response.

Content negotiation policy is represented in Core package with `          Representations\AbstractContenNegotiationPolicy ` class. Content negotiation policies should provide
        only static data and functions, so you do not need to instance them.

Content negotiation policies contains _representations_ that are associative array of a key ( a medium
        mimetype or a language id) and a _content processor function_ that accepts as the only parameter the
        resource model (i.e. a PHP variable) and process it . A content negotiation policies provides:

<dl><dt>response representations</dt><dd> implemented with the protected associative array ` $renderers ` that associate a mime type
          with a [renderer](#renderer). Content negotiation policy provide the public ` renderers() `
          function that returns $renders variable. </dd><dt>request representations</dt><dd> implemented with the protected associative array ` $parsers ` that associate a mime type with
          a parser. Content negotiation policy provide the public ` restorers() ` function that returns
          $restorers variable . </dd><dt>language translators</dt><dd> implemented with the protected associative array ` $translators ` that associate a language id
          with a content processor function that translate language content elements in Resource Model according the
          language id. Content negotiation policy provide the public ` translators() ` function that
          returns $translators variable. </dd></dl>
          
All Content negotiation policies provide the static method ` render()` that renders a Resource model.
This method is normally called by endpoint runtime engine, but you can call it by yourself (see above). Core
package provides a set of "ready to use" content negotiation policies detailed above. 

## Negotiating contents in Response

Response content negotiation involves Renderers and Serializers.

In Core package you can choose how to manage content negotiation in HTTP responses selecting one of these
        methods:

1.  using a policy and taking into "Accept" header in client request, you can attach a policy "per route" or
              using using the same policy policy for all routes. see above from more info about "accept processing" 
2.  create dynamically a rendering policy in "accept processing" 
3.  directly provide a resource state representation. In this&nbsp; case you are responsible to set proper HTTP
              Content-type header and to returning a string or an object that supports ` __toString()` magic
              methods or an open stream to the endpoint run-time engine. 
4.  call directly policy render() method and pass the result to rendering policy.

In any case remember that it is your responsibility to use a policy that it is compatible with your resource
        model or you can easily incur in a run-time error.

### Accept processing

"Accept" is a set of HTTP header optionally specified by client that states the client preferences for
        resource representation.Endpoint binds the available renderers to client desiderata with the ` accept() `
        method.

For example, these two routes use same controller but they my accept different policies for content
        negotiation:

    $this->get('/simplest', 'myHelloController'); // no content negotiation
    $this->get('/simple', 'myHelloController')**->accept(Standard::renderes())**;

Note that if no content negotiation policy is defined, the controller itself must take the responsibility for
        model state rendering.

In this example there are three different routes with different controllers that share same content management
        policy:

    $this->get('/route1', 'Controller1');
    $this->get('/route2', 'Controller2');
    $this->get('/route3', 'Controller3');

    $this->**always('Accept',Standard::renderers())**;

The content negotiation policy by accept processing can be _forced_ to select a particular
representation bypassing HTTP protocol specifying a medium in resource uri the client request in variable *_output*
. For instance to force xml rendering of a resource ignoring the content preferences specified in HTTP header
you can use : ` HTTP://...endpoint_uri?_output=application/xml ` .

You can also define dynamically representations

See Respect\Rest documentation for some examples about ` accept() ` usage.

## Negotiating contents in Requests

It is a process performed by controller that restore the status of a Resource model from its representation
        provided by client in HTTP request. The process is performed by ` setState()` Controller method that
        accepts as first parameter a resource model instance and as second parameter an associative array of property
        => value or a parsable string representation of a PHP variable (that typically, but non necessary, was
        produced by [` var_export()`](http://php.net/manual/en/function.var-export.php) PHP
        function).

## Customizing Content Negotiation Policies

To create your own content negotiation policy you just deriving an existing Custom Negotiation Class.

### Redefine an existing rendering

In this example see how to redefine standard html renderer using a custom templating design:

    use BOTK\Core\Representations\Standard;
    class MyStandard extends Standard
    {
        public static function htmlRenderer($data)
        {
            if(ob_start()){
                require 'myhtml_template.php';
                $result = ob_get_contents();
                ob_end_clean();
            }
            return $result;
        }
    }

### Change the list of supported renderers

As example remove html and text support from standard policy:

    use BOTK\Core\Representations\Standard;
    class MyStandard extends Standard
    {
        protected static $renderers = array(
            'application/json'  => 'json_encode',// PHP standard function
            'application/xml'   => 'xmlRenderer', 
            'application/x-php' => 'serialize',// PHP standard function
        ); 
    }

### Create a new content negotiation

Example of new policy reusing an existing renderer, redefining one one and creating a new one;

    use BOTK\Core\Representations\Standard;
    class MyPolicy extends AbstractContentNegotiationPolicy
    {    
        protected static $renderers = array(
            'application/json' => array('\\BOTK\\Core\\Representations\\Standard', 'jsonRenderer',
            'text/plain' => 'plaintextRenderer',
            'application/myapplication+xml' => 'myRenderer',
        ); 

        public static function plaintextRenderer($data)
        {
            header('Content-Type: text/plain');
            return print_r($data, true);
        }

        public static function myRenderer($data)
        {
            header('Content-Type: application/myapplication+xml');
            return Sample::xmlSerializer($data, true, 
               'HTTP://myurl/myversyspecialxmlstyle.css',
               'HTTP://myurl/myversyspecialxmlstyle.xslt');
        }

    }

## Predefined content negotiation policies

### Standard

This content negotiation policy is designed for applications that use generic PHP data structure as Resource
        Model.

It provide following response and request representations:

      Standard class define following renderer functions: 
<dl><dt>Standard::jsonRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> Serializes data structure using ` json_encoder() ` standard PHP function </dd><dt>Standard::xmlStandardRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> Create a valid xml using [xmlSerializer()](#xmlSerializer). It uses `            static::$xmlProcessingInstructions ` static array to drive serializer. </dd><dt>Standard::htmlRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> Serializes data structure using [htmlSerializer()](#htmlSerializer). It uses `            static::$htmlMetadata ` static array , to drive serializer. If ` static::$htmlMetadata `is
          an array it assume that each array element is an html&nbsp; header, if it is a string it assume that it is the
          url of a css. </dd><dt>Standard::plaintextRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> Serializes data structure using [textSerializer()](#textSerializer). </dd><dt>Standard::serialphpRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> serializes data structure using ` serialize() ` standard PHP function. </dd><dt>Standard::phpRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> Renders the input data sing ` var_export() ` standard PHP function . It can be used for
          passing PHP code to the client to fulfil "code on demand" REST architecture constraint. It is client
          responsibility to interpret and execute the code. </dd><dt>Standard::htmlTemplateRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> a very simple template engine to reneder html using a custom PHP script. As template file it uses the path
          contained in ` static::$htmlTemplate ` that defaults to 'templates/html_template.php'. </dd></dl>

All Standard renderers display only visible properties of input $data.

Standard class define following serializers:

<dl><dt id="htmlSerializer">htmlSerializer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data, array $metadata = array(), $type = '', $header='', $footer='')</dt><dd> display data as semantic tagged html5, if metadata is empty it produce just an html fragment . It use
          textSerializer to show data. </dd><dt id="textSerializer">plaintextSerializer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> it is like print_r PHP function but operates only on visible (public) vars on objects. </dd><dt>phpSerializer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data, $templateFile)</dt><dd> it is a pseudo-serializer that symply include a PHP script that must render $data. </dd><dt id="xmlSerializer">xmlSerializer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data, array $processingInstructions=array(),$rootElement=null)</dt><dd> Serialize data structure using [Xmlon](#Xmlon) class encode method. If empty
          $prorcessingInstruction provided an invalid xml fragment is produced. </dd><dt>templateSerializer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data, $template)</dt><dd> it is a micro template engine that just include $template file passing all public variables of data to it
          as $data. </dd><dt>htmlWebLinkSerializer(WebLink $link)</dt><dd> serialize a weblink as an html link header tag. </dd></dl>

Standard class define following parser functions:

<dl><dt>Standard::jsonRestore(mixed $resource, string $representation)</dt><dd> UnSerializes $representation structure in $resource using ` json_decoder() ` standard PHP
          function. Decoder representation type must match $resource one. </dd><dt>Standard::xmlStandardRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> UnSerializes $representation structure in $resource using [xmlParser()](#xmlParser).Decoder
          representation type must match $resource one. </dd><dt>Standard::serialphpRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> UnSerializes $representation structure in $resource using ` unserialize() ` standard PHP
          function. Decoder representation type must match $resource one. </dd></dl>

Standard class define following parsers functions:

<dl><dt>Standard::xmlStandardRenderer([mixed](http://www.php.net/manual/en/language.pseudo-types.php#language.types.mixed)
          $data)</dt><dd> UnSerializes $representation structure in $resource using [Xmlon](#Xmlon)
          decode method </dd></dl>

#### Runtime customizing of Standard policy

Some default serializers allow a sort of personalization if some variables are defined in the policy. For
        example to change the css in html generate by Standard policy use:

        /**
         * Allow to personalize xml header in xmlStandardRenderer
         */
        public static $xmlProcessingInstruction = array(
            '<?xml version="1.0" encoding="UTF8"?>',
        );

        /**
         * Allow htmlRenderer to personalize html headers
         */
        public static $htmlMetadata = array(
            '<link rel="stylesheet" type="text/css" href="HTTP://www.w3.org/StyleSheets/TR/base" />'
        );

        /**
         * If using htmlTemplateRenderer allow you to define the template path
         */
        public static $htmlTemplate = 'templates/html_template.php';

### Error

Core Pachage provides a specialization of Standad Content Management policy&nbsp; to support `          Core\Models\HttpProblem` data model. It is used by Error Manager to display http errors.

It provide following response and request representations:

 Standard class redefine from Standard following renderer functions: 
<dl><dt>Error::htmlErrorRenderer(HttpProblem $data)</dt><dd> It uses ` static::$htmlMetadata ` static array , to print html header. If `            static::$htmlMetadata `is an array it assume that each array element is an html&nbsp; header, if it
          is a string it assume that it is the url of a css. </dd><dt>Error::jsobErrorRenderer(HttpProblem $data)</dt><dd> Render http proiblem as [application/api-problem+json
            ](ttp://tools.ietf.org/html/draft-nottingham-http-problem-04)</dd></dl>

#### Runtime customizing of Error policy

You can change the display style error just setting ` static::$htmlMetadata ` . For example to
        change the css in html generate by Standard policy use:

    public static $htmlMetadata ="HTTP://www.w3.org/StyleSheets/TR/base" />';

# HTTP Caching

HTTP cache management is related to the to generation of ETag and "Last-Modified" header. Beside this Etag and
      Last-Modified should be cheched against if-none-match and if-modified-since header requests.

ETag is used for two purposes:

    *   to avoid to send a response body already known by client 
*   to manage concurrency

Unfortunatelly the two objectives do not match well: to reach firs objective you should calculate the ETag on
      Resource Representation. To reach second objective sholud be preferable to calculate ETag on Resource model. Core
      Package adopt a simple solution. You can call caching multiple times. Each time a new ETag wil be calculated
      appending a new value to existing one.

There are two entrypoint for managing caching:

    *   at Controller level through method ` resouceCachingProcessor() ` that accepts a resource and,
            optionally one time constant exported by [Caching](#Caching) class 
*   atEndpoint level through method ` representationCachingProcessor() ` that accepts one of caching
            algorithm exported by [Caching](#Caching) class.

You can call none, one or both entry points to manage cache For example:

        class HellowordController extends Controller
    {
        public function get()
        {
            return self::stateTransfer(
                **$this->resouceCachingProcessor(new HelloworldModel)**,
                WebLink::factory('HTTP://e-artspace.com/')->rel('sender')
            );
        }
    }

    class HelloWorld extendsEndpoint
    {
        protected function setRoutes()
        {
            $this->get('/', 'HellowordController')
                ->accept(Standard::representations())
                **->through($this->representationCachingProcessor())**
        }
    }

This will return to client following header:

        after resource caching: ETag : **"123123a1523671351"**
    after representation caching:  ETag: **"123123a1523671351/****123126371235611"**

A client can parse ETag and decide if use all Etag or just the first part (i.e. the weak ETag). Core package
      caching supports partial ETag matching.

Both ` resouceCachingProcessor() ` and ` representationCachingProcessor() ` accetp an
      additional parameter to specify a cache-control header max-age. In particular ` resouceCachingProcessor() `
      accepts a positive integer that represents the estimate of number of second the resource should remain fresh (0=no
      chaching), while ` representationCachingProcessor() ` espects one of predefined algorithm in [Caching](#Caching)
      class. For example:

        representationCachingProcessor(); // 0 sec of Max-Age equivalent to
    representationCachingProcessor(Caching::NO); // 0 sec of Max-Age
    representationCachingProcessor(Caching::DOS_PROTECTION); // 3 sec of Max-Age
    representationCachingProcessor(Caching::SHORT); // 30 sec of Max-Age
    representationCachingProcessor(Caching::CONSERVATIVE); // 180 sec of Max-Age
    representationCachingProcessor(Caching::AGGRESSIVE); // one hour of Max-Age
    representationCachingProcessor(Caching::VERY_AGGRESSIVE); // one day of Max-Age
    ...
    resourceCachingProcessor(new HelloworldModel,3) // 3 sec of Max-Age
  

# Error Management

Core package ErrorManager class provides helpers to automatically translate all Exceptions in HTTP error
        response, providing a client with all information about occurred error. Error management try to be consistent
        with last available [http_problem proposal](http://tools.ietf.org/html/draft-nottingham-http-problem-04)
        RFC managing ` BOTK\Core\Models\HttpProblem` data structure. ErrorManager class uses [Content
          Management Error Representations](#ErrorRepresentation) to render an http problemrepresentation.

Core package and ErrorManager adopts [PHP exception
          management](http://php.net/manual/en/language.exceptions.php).

### To enable error management

Error management is a singleton to be use in main web service script:

    try {                                                      
        echoEndpointFactory::make('MyEndPoint')->run();
    } catch ( Exception $e) {
        echo  ErrorManager::getInstance()->render($e); 
    }

ErrorManager can translate any PHP error in Exception for an homogeneous error management:

    $errorManager = ErrorManager::getInstance()->registerErrorHandler(); 
    try {                                                      
        echoEndpointFactory::make('MyEndPoint')->run();
    } catch ( Exception $e) {
        echo $errorManager->render($e); 
    }

### To generate an error

in your code service simply:

    $problem = new HttpProblem(
      404, // the Http status to return
      'short description of the error',
      'http://example.org/error/description/', // uri that describe this error
      'http://example.org/error/class/description/' // uri that describe this error type
    );
    throw new HttpErrorException($problem);

You can use ` null` to indicate to Error management to use defaults for parameters: all parameters
        in HttpProblem have a default value, so they do not need to be specified:

    throw new HttpErrorException(); // throws a 500 http errorthrow new HttpErrorException(new HttpProblem(404)); // throws a 404 http error

You are not forced to use HttpErrorException, you can throw any PHP Exception, if error code is between 400
        and 599 appropriate HTTP Status will be selected.

Finally you can ask content manager to direct serialize an http problem taking into account request
        preferences:

    echo ErrorManager::getInstance()->serializeHttpProblem(new HttpProblem(404)); 

### To customize error display policy

You can instruct Error Management to use your custom Content Negotiation Policy to render errors:

    class myErrorRenderingPolicy extends BOTK\Core\Representations\Error{
    	public static function htmlRenderer($error) {return 'Opss!';}
    }
    ErrorManager::getInstance()->setContentPolicy('myErrorRenderingPolicy'); 

### HTTP Error Status codes

Core package can throw this errors types:

<dl><dt>400 Bad Request</dt><dd> when a field validation fails </dd><dt>404 Not Found</dt><dd> on invalid or not found endpoint in application </dd><dd> when no matching route paths are found </dd><dt>401 Unauthorized</dt><dd> when the client sends an unauthenticated request to a route using authBasic routine. </dd><dt>405 Method Not Allowed</dt><dd> when a matching path is found but the method isn't specified. </dd><dt>406 Not Acceptable</dt><dd> when the route path and method matches but content-negotiation doesn't </dd><dt>500 Internal Server Error</dt><dd> on invalid class types or unknown server errors </dd></dl>

When an HTTP Error status is returned, the response body will contain an http problem representation; if
        possible the http problem serialization takes care of the _accept HTTP _header in request, it returns a
        json structure otherwise.



# Other classes, interfaces and helpers

## Singleton Class

Just a very basic implementation of Singleton design pattern in php 5.3 . This implememtaton works with
        multiple sublclasses.

## SimpleTemplateEngine Class

This class implements a very simple template engine providing some public methods:

*   `__construct($template='',$openDelimiter = '{', $closeDelimiter = '}')`: the constructor
*   `factory($template='',$openDelimiter = '{', $closeDelimiter = '}')`: the static method to cal
              constructur
*   `setTemplate($string)`: sets the template from a string.
*   `setFromFile($file)`: sets the template from a string.
*   `setVars(array $values, $merge = TRUE)`: sets one or more template variables.
*   `addVar($tag,$value)`:add a single template variables.
*   `render()`: renders the template and returns the result as a string. Also available as __toString
*   `renderToFile($target)`: renders the template and writes the result to a file.

## Http Class

This helper&nbsp; provides a set of few functions that are not natively present in supported version of PHP,
        mainly related to parsing and managing of HTTP headers.

## Caching

This class provides a set of constant and functions for managing HTTP caching protocol

## Cacheable interface

Php classes can optionally a couple of functions to help HTTP caching management:

<dl><dt>string getETag()</dt><dd> returns a string that uniquely identify the resource state. Used by ` Caching::setETagHeader() `</dd><dt>DateTime getLastUpdateDate()</dt><dd> returns a DateTime structure that identify the last update time of the resouce state. Used by `            Caching::setLastModifiedHeader() `</dd></dl>

## WebLink

this class models WebLink according to rfc5988


## License

 Copyright © 2016 by  Enrico Fagnoni at [LinkedData.Center](http://LinkedData.Center/)®

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

  