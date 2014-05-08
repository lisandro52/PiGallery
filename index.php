<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>PiGallery</title>

    <!--jquerry ui-->
    <link rel="stylesheet" href="./css/ui-bootstrap/jquery-ui-1.10.3.custom.css">
    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">


    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- bootstrap image gellery-->
    <link rel="stylesheet" href="css/blueimp-gallery.min.css">
   <!-- <link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">-->
    <link rel="stylesheet" href="css/bootstrap-image-gallery.min.css">

    <!-- Own css-->

    <link href="./css/override/boostrap-override.css" rel="stylesheet">
    <link href="./css/override/galery.css" rel="stylesheet">

    <?php
        /*Preload directory content*/
        require_once __DIR__ ."./db/DB.php";
        require_once __DIR__ ."./model/Helper.php";

        use \piGallery\model\Helper;

        $dir =  Helper::get_REQUEST('dir','/');

        $content = \piGallery\db\DB::getDirectoryContent($dir);
    ?>

    <script language="JavaScript">
        var PiGallery = PiGallery || {};

        //Preloaded directory content
        PiGallery.preLoadedDirectoryContent= <?php echo Helper::contentArrayToJSON($content); ?>;

    </script>


</head>

<body>



<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">PiGalery</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Gallery</a></li>
              <!--  <li><a href="#">Admin</a></li>
                <li><a href="#">Monitor</a></li> -->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../navbar/"><span class="glyphicon glyphicon-share-alt"> Share</span></a></li>
                <li><a href="#">bpatrik</a></li>
                <li><a href="./">Log out</a></li>
            </ul>
            <form class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <input type="text" id="auto-complete-box"  class="form-control" placeholder="Search">
                </div>
                <button type="submit" id="search-button" class="btn btn-default">Submit</button>
            </form>
        </div><!--/.nav-collapse -->
    </div>
</div>


<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" >
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="full-screen"><span class="glyphicon glyphicon-fullscreen"></span> </a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container gallery-container">
    <ol id="directory-path" class="breadcrumb">
    </ol>
    <div id="gallery">

        <div id="directory-gallery"  data-bind="template: { name: 'directoryList' }"  ></div>


        <hr/>


        <div id="photo-gallery"  data-bind="template: { name: 'photoList' }"  >
        </div>


    </div>

</div> <!-- /container -->

<!-- render templates-->
<script type="text/html" id="directoryList">
    <% _.each(directories(), function(directory, index) { %>
        <div class="gallery-directory-wrapper"   data-directory-id="<%= index %>" style="height: <%= directory.renderSize %>px; width: <%= directory.renderSize %>px;" >

            <div class="gallery-directory-image" data-bind="event: {mousemove : $root.directoryMouseMoveHandler }"  style="height: <%= directory.renderSize %>px; width: <%= directory.renderSize %>px;">
                <a data-bind="attr: { href: '?dir='+ directory.path + directory.directoryName + '/', title: directory.directoryName , 'data-path': directory.path+directory.directoryName+'/'}, event: {click: $root.directoryClickHandler}  " >

                    <% if(directory.samplePhotos.length > 0) { %>
                    <img  data-bind="attr: { src: $root.getThumbnailUrl($element, directory.samplePhoto(), directory.width(), directory.height()) }, style: {width: directory.width() + 'px', height: directory.height() + 'px'}">
                    <% } %>

                    <% if(directory.samplePhotos.length == 0) { %>
                    <img  src="img/gallery-icon.jpg" style="width: 100%;">
                    <% } %>
                </a>
            </div>
            <div class="gallery-directory-description">
                <span class="pull-left" data-bind="text: directory.directoryName"> </span>
            </div>

        </div>

    <% }) %>
</script>


<script type="text/html" id="photoList">
    <% _.each(photos(), function(photo) { %>
     <div class="gallery-image"  style="height: <%= photo.renderHeight %>px; width: <%= photo.renderWidth %>px; display: none;" data-bind=" event: {mouseover: $root.imageMouseOverHandler, mouseout: $root.imageMouseOutHandler}" >
        <a href=<%= photo.path + photo.fileName %> data-galxlery="">
            <img onload="$(this).parent().parent().fadeIn();"  data-bind="attr: { src: $root.getThumbnailUrl($element, photo, photo.renderHeight, photo.renderWidth) }"  style="height: <%= photo.renderHeight %>px; width: <%= photo.renderWidth %>px;" />
        </a>
        <div class="gallery-image-description">
            <span class="pull-left" style="display: inline; position: absolute"><%= photo.fileName %></span>

            <div class="galley-image-keywords">
            <% _.each(photo.keywords, function(keyword) { %>
                <a href="#" data-keyword="<%= keyword %>" data-bind="event: {click: $root.keywordClickHandler }">#<%= keyword %></a>,
             <% }) %>
            </div>
        </div>
    </div>

    <% }) %>
</script>


<script src="js/lib/require.min.js" data-main="js/main.js"></script>


</body>
</html>
