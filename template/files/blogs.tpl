<div class="jumbotron">
    <h1>Blogs</h1>
</div>

<div class="col-sm-12 blog-main">

    <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Library</a></li>
        <li class="active">Data</li>
    </ol>
    <div class="blog-post">

        <!-- START BLOCK : MELDING -->

        <div class="alert alert-info" role="alert">
            <p>{MELDING}</p>
        </div>
        <!-- END BLOCK : MELDING -->

        <!-- Three columns of text below the carousel -->
        <!-- START BLOCK : BEGIN -->
        <div class="row">
            <!-- END BLOCK : BEGIN -->

            <!-- START BLOCK : BLOG -->
            <div class="blog-content">
                <h2>{TITLE}</h2>
                <p>{CONTENT}</p>
                <p><a class="btn btn-default" href="index.php?pageid=8&blogid={BLOGID}" role="button">Read more &raquo;</a></p>
            </div><!-- /.col-lg-4 -->
            <!-- END BLOCK : BLOG -->

            <!-- START BLOCK : END -->
        </div><!-- /.row -->
        <!-- END BLOCK : END -->



        <!-- START BLOCK : DETAILS -->
        <div class="col-sm-12 blog-main">

            <div class="blog-post">
                <h2 class="blog-post-title">{TITLE}</h2>
                <p class="blog-post-meta">January 1, 2014 by <a href="#">{USERNAME}</a></p>

                <p>{CONTENT}</p>

                <!-- START BLOCK : COMMENTFORM -->
                <form class="form-horizontal" action="{ACTION}" method="post">
                    <div class="form-group">
                        <label for="inputcomment" class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="inputcomment" placeholder="comment" name="comment">{COMMENT}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-default">Plaats Comment</button>
                            <input type="hidden" name="blogid" value="{BLOGID}">
                        </div>
                    </div>
                </form>

                <!-- END BLOCK : COMMENTFORM -->

                <!-- START BLOCK : COMMENTLIST -->
                <small>posted by:{USERNAME}</small>
                <p>{TEXT}</p>



                <a href="index.php?pageid=10&action=wijzigen&commentid={COMMENTID}">Wijzigen</a>
                <a href="index.php?pageid=10&action=verwijderen&commentid={COMMENTID}">Verwijderen</a>

                <hr>



                <!-- END BLOCK : COMMENTLIST -->




                <p><a class="btn btn-default" href="index.php?pageid=8&{BLOGID}" role="button">Back </a></p>
                <hr>
            </div><!-- /.blog-post -->
        </div>

        <!-- END BLOCK : DETAILS -->



    </div><!-- /.blog-post -->
</div>
