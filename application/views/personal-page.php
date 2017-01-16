<?php
$userdata = $this->session->userdata('userdata');
if($userdata['picture_url'] == ""){
    $userdata['picture_url'] =  base_url().'/images/avatar.jpg';
}
?>
<div class="col-md-12">
    <div id="sidebar-wrapper123" class="col-md-2">
        <div id="sidebar123">
            <ul class="nav nav-bar 123list-group" style="text-align: center;">
                <li>
                    <a class="list-group-item" href="#"><i class="icon-home icon-1x"></i><?php echo  $userdata['first_name'].' '.$userdata['last_name'];?></a>
                </li>
                <li>
                    <a class="list-group-item" href="#"><i class="icon-home icon-1x"></i><?php echo '<img src="'.$userdata['picture_url'].'" alt="" width="150" height="150"style="border-radius: 75px; border: 5px solid #3b5998;"/>';?></a>
                </li>
                <!-----<li>
                    <a class="list-group-item" href="#"><i class="icon-home icon-1x"></i><?php echo $userdata['email']; ?></a>
                </li>---->
                <li>
                    <a class="list-group-item" href="<?php echo base_url(); ?>index.php/welcome/personalpage/<?php echo $userdata['user_id'];  ?>"><i class="icon-home icon-1x"></i>Personal Page</a>
                </li>
                <li>
                    <a class="list-group-item" id="myBtn" href="javascript:;"><i class="icon-home icon-1x"></i>Invite Friends</a>
                </li>
                <!-----<li>
                    <a class="list-group-item" href="#"><i class="icon-home icon-1x"></i>Create Group</a>
                </li>---->
            </ul>
        </div>
    </div>

    <div id="main-wrapper" class="col-md-7">
        <div class="container wallwidth">
            <div class="middle_box">
                <div id="myModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p><h3>Send email to your friend to join SocialFeed :</h3>
                        <form method="post" id="emailform">
                            <input type="text" name="iniviteemail" id="iemail" placeholder="your friend email" size="40" />
                            <input type="submit" id="emailsend" value="Invite" />
                        </form>
                        </p>
                    </div>

                </div>
            <h3>Your Posts</h3>
            <!---<div class="feed_form">
                <form action="" id="frmpost" method="post" enctype="multipart/form-data">
                    <div class="row_ele">
                        <textarea name="post_feed" id="post_feed" class="textarea" rows="3" placeholder="What's on your mind?"></textarea>
                    </div>
                    <div class="row_ele">
                        <input type="file" name="userfile" id="userfile" size="20" />
                    </div>
                    <div class="row_ele">
                        <input class="btn" id="btnpost" type="button" name="submit" value="Post"/>
                    </div>
                </form>
            </div>--->
                <div class="clear"></div>
                <div class="feed_div" id="feed_div">
                    <?php
                    if($postdata){
                        foreach($postdata as $post){
                            ?>
                            <div class="feed_box" id="postbox_<?php echo $post->post_id; ?>">
                                <div class="feed_left">
                                    <p><img class="userimg" src="<?php if($userdata['user_id'] == $post->user_id){echo $userdata['picture_url'];}elseif(!empty($post->userpic)){echo $post->userpic;}else { echo base_url(); ?>images/usericon.gif<?php } ?>"/></p>
                                    <p><?php echo $post->username; ?></p>
                                </div>
                                <div class="feed_right">
                                    <p><?php echo $post->content; ?></p>
                                    <p><img src="<?php echo base_url(); ?>uploads/<?php echo $post->picturename; ?>" width="200"></p>
                                    <div id="uploaded_image">

                                    </div>
                                    <p class="likebox">
                                        Total Like : <?php echo $post->total_like; ?>&nbsp;|&nbsp;
                                        <?php if(isset($post->like_id) && $post->like_id != ""){ ?>
                                            <a class="link_btn dis_like_btn" postid="<?php echo $post->post_id; ?>" href="javascript:;">Dislike</a>&nbsp;|&nbsp;
                                        <?php }else{ ?>
                                            <a class="link_btn like_btn" postid="<?php echo $post->post_id; ?>" href="javascript:;">Like</a>&nbsp;|&nbsp;
                                        <?php } ?>
                                        <a class="link_btn" id="commentssh_<?php echo $post->post_id; ?>" postid="<?php echo $post->post_id; ?>" onclick="showhidecomments('<?php echo $post->post_id; ?>');" href="javascript:;">Comment</a>
                                    </p>
                                    <div class="clear"></div>
                                    <?php if(!empty($comments)){ ?>
                                        <div class="comment_div"id="commentss_<?php echo $post->post_id; ?>">
                                            <?php foreach($comments as $comment) {
                                                if($comment->post_id == $post->post_id){
                                                    ?>

                                                    <div class="clear"></div>
                                                    <div class="comment_ele" id="commentdel_<?php echo $comment->comment_id; ?>">
                                                        <p><a class="link_btn" href="javascript:;"><?php echo $comment->username; ?></a></p>

                                                        <p><?php echo $comment->comment; ?></p>
                                                        <?php if($userdata['user_id'] == $comment->user_id || $userdata['user_id'] == $post->user_id){ ?>
                                                            <p><a class="link_btn" href="javascript:;"onClick="callCrudAction('delete',<?php echo $comment->comment_id; ?>)">Delete</a></p>
                                                        <?php } ?>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                        </div>
                                    <?php } ?>
                                    <div class="clear"></div>
                                    <p>
                                    <form id="commentform_<?php echo $post->post_id; ?>" method="post">
                                        <input type="hidden" name="action" value="comment"/>
                                        <input type="hidden" name="post_id" value="<?php echo $post->post_id; ?>"/>
                                        <input class="input comment_input" type="text" name="comment" id="comment_<?php echo $post->post_id; ?>" placeholder="your comment"/>
                                        <input class="submitbtn btn" postid="<?php echo $post->post_id; ?>" type="button" name="sendbtn" value=">"/>
                                    </form>
                                    </p>
                                </div>
                                <div class="clear"></div>
                            </div>
                        <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>