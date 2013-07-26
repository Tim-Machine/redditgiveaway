<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>RedditGift.es</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootswatch/2.3.2/slate/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>
    <!-- Part 1: Wrap all page content here -->
    <div id="wrap" class="container">

      <div class="row">
        <h1>Reddgift</h1>
        <div class="well">
          Sometimes it nice to have a easy way to give away something. Give us the URL to your reddit give away and will provide you with a random winner. If you dont like the winner that is returned you can easily  serach for another one.
        </div>
      </div>

      <div id="submit" class="row">
        <!-- start form -->
        <div class="span12">
          <form id="search" class="form-search">
            <div class="input-append">
              <input type="text" id="url" class="search-query span10" placeholder="http://reddit.com">
              <button type="submit" class="btn">Search</button>
            </div>
          </form>
          <div id="errors" class="label label-important" ></div>
        </div>
        <!-- end form -->
      </div>
      <div class="row">
        <!-- start orginal post -->
        <div id="orginalPost" class="span6">
          <h3 id="op_title"></h3>
          <div id="op_content">win something really cool!</div>
        </div>
        <!-- end orginal post -->

        <!-- start winner -->
        <div id="winner" class="span6">
          <h3 id="win_author">ian</h3>
          <div id="win_content"> i like pie </div>
        </div>
        <!-- end winnder -->
      </div>
      <div id="push"></div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="muted credit">redditgift.es &copy; 2013  </p>
      </div>
    </div>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/main.js"></script>

  </body>
</html>
