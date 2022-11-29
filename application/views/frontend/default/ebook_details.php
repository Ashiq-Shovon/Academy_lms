<?php

$ebook_details = $this->ebook_model->get_ebook_by_id($ebook_id)->row_array();

$instructor_details = $this->user_model->get_all_user($ebook_id)->row_array();
?>
<section class="course-header-area">
  <div class="container">
    <div class="row align-items-end">
      <div class="col-lg-8">
        <div class="course-header-wrap">
          <h1 class="title"><?php echo $ebook_details['title']; ?></h1>
          <p class="subtitle"><?php echo $ebook_details['description']; ?></p>
          <div class="rating-row">
            <?php
            $total_rating =  $this->ebook_model->get_ratings($ebook_details['ebook_id'], true)->row()->rating;
            $number_of_ratings = $this->ebook_model->get_ratings($ebook_details['ebook_id'])->num_rows();
            if ($number_of_ratings > 0) {
              $average_ceil_rating = ceil($total_rating / $number_of_ratings);
            } else {
              $average_ceil_rating = 0;
            }

            for ($i = 1; $i < 6; $i++) : ?>
              <?php if ($i <= $average_ceil_rating) : ?>
                <i class="fas fa-star filled" style="color: #f5c85b;"></i>
              <?php else : ?>
                <i class="fas fa-star"></i>
              <?php endif; ?>
            <?php endfor; ?>
            <span class="d-inline-block average-rating"><?php echo $average_ceil_rating; ?></span><span>(<?php echo $number_of_ratings . ' ' . site_phrase('ratings'); ?>)</span>
            
          </div>
          <div class="created-row">
            <span class="created-by">
              <?php echo site_phrase('created_by'); ?>
             
                <a class="text-14px fw-600 text-decoration-none" href="<?php echo site_url('home/instructor_page/' . $ebook_details['user_id']); ?>"><?php echo $instructor_details['first_name'] . ' ' . $instructor_details['last_name']; ?></a>
             
            </span>
            <br>
            <?php if ($ebook_details['updated_date'] > 0) : ?>
              <span class="last-updated-date d-inline-block mt-2"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $ebook_details['updated_date']); ?></span>
            <?php else : ?>
              <span class="last-updated-date d-inline-block mt-3"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $ebook_details['added_date']); ?></span>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
      <div class="col-lg-4">

      </div>
    </div>
  </div>
</section>

<section class="course-content-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 order-last order-lg-first radius-10 mt-4 bg-white p-30-40">

        <div class="description-box view-more-parent">
          <div class="view-more" onclick="viewMore(this,'hide')">+ <?php echo site_phrase('view_more'); ?></div>
          <div class="description-title"><?php echo site_phrase('course_overview'); ?></div>
          <div class="description-content-wrap">
            <div class="description-content">
              <?php echo $ebook_details['description']; ?>
            </div>
          </div>
        </div>
        <div class="compare-box view-more-parent">
          <div class="view-more" onclick="viewMore(this)">+ <?php echo site_phrase('view_more'); ?></div>
          <div class="compare-title"><?php echo site_phrase('other_related_ebook'); ?></div>
          <div class="compare-courses-wrap">
            <?php
            $this->db->limit(5);
            $other_related_ebooks = $this->ebook_model->get_ebooks($ebook_details['category_id'])->result_array();
            foreach ($other_related_ebooks as $other_related_ebook) :
              if ($other_related_ebook['ebook_id'] != $ebook_details['ebook_id'] && $other_related_ebook['is_active'] == 1) : ?>
                <div class="course-comparism-item-container this-course">
                  <div class="course-comparism-item clearfix">
                    <div class="item-image float-start  mt-4 mt-md-0">
                      <a href="<?php echo site_url('ebook/ebook_details/' . slugify($other_related_ebook['title']) . '/' . $other_related_ebook['ebook_id']); ?>"><img src="<?php $this->ebook_model->get_ebook_thumbnail_url($other_related_ebook['ebook_id']); ?>" alt="" class="img-fluid"></a>
                    </div>
                    <div class="item-title float-start">
                      <div class="title"><a href="<?php echo site_url('ebook/ebook_details/' . slugify($other_related_ebook['title']) . '/' . $other_related_ebook['ebook_id']); ?>"><?php echo $other_related_ebook['title']; ?></a></div>
                      <?php if ($other_related_ebook['updated_date'] > 0) : ?>
                        <div class="updated-time"><?php echo site_phrase('updated') . ' ' . date('D, d-M-Y', $other_related_ebook['updated_date']); ?></div>
                      <?php else : ?>
                        <div class="updated-time"><?php echo site_phrase('updated') . ' ' . date('D, d-M-Y', $other_related_ebook['added_date']); ?></div>
                      <?php endif; ?>
                    </div>
                    <div class="item-details float-start">
                      <span class="item-rating">
                        <i class="fas fa-star"></i>
                        <?php
                        $total_rating =  $this->ebook_model->get_ratings( $other_related_ebook['ebook_id'], true)->row()->rating;
                        $number_of_ratings = $this->ebook_model->get_ratings($other_related_ebook['ebook_id'])->num_rows();
                        if ($number_of_ratings > 0) {
                          $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                        } else {
                          $average_ceil_rating = 0;
                        }
                        ?>
                        <span class="d-inline-block average-rating"><?php echo $average_ceil_rating; ?></span>
                      </span>
                      <span class="enrolled-student">
                        <i class="far fa-user"></i>
                      </span>
                      <?php if ($other_related_ebook['is_free'] == 1) : ?>
                        <span class="item-price mt-4 mt-md-0">
                          <span class="current-price"><?php echo site_phrase('free'); ?></span>
                        </span>
                      <?php else : ?>
                        <?php if ($other_related_ebook['discount_flag'] == 1) : ?>
                          <span class="item-price mt-4 mt-md-0">
                            <span class="original-price"><?php echo currency($other_related_ebook['price']); ?></span>
                            <span class="current-price"><?php echo currency($other_related_ebook['discounted_price']); ?></span>
                          </span>
                        <?php else : ?>
                          <span class="item-price mt-4 mt-md-0">
                            <span class="current-price"><?php echo currency($other_related_ebook['price']); ?></span>
                          </span>
                        <?php endif; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="about-instructor-box">
          <div class="about-instructor-title">
            <?php echo site_phrase('about_instructor'); ?>
          </div>
         
            <div class="row justify-content-center">
                <div class="col-md-4 top-instructor-img w-sm-100">
                  <a href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']); ?>">
                    <img src="<?php echo $this->user_model->get_user_image_url($instructor_details['id']); ?>" width="100%">
                  </a>
                </div>
                <div class="col-md-8 top-instructor-details text-center text-md-start">
                    <h4 class="mb-1 fw-600 v"><a class="text-decoration-none" href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']); ?>"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></a></h4>
                    <p class="fw-500 text-14px w-100"><?php echo $instructor_details['title']; ?></p>
                    <div class="rating">
                      <div class="d-inline-block">
                        <span class="text-dark fw-800 text-muted ms-1 text-13px"><?php echo $this->crud_model->get_instructor_wise_course_ratings($instructor_details['id'], 'course')->num_rows().' '.site_phrase('reviews'); ?></span>
                        |
                        <span class="text-dark fw-800 text-13px text-muted mx-1">
                            <?php $course_ids = $this->crud_model->get_instructor_wise_courses($instructor_details['id'], 'simple_array');
                          $this->db->select('user_id');
                          $this->db->distinct();
                          $this->db->where_in('course_id', $course_ids);
                          echo $this->db->get('enrol')->num_rows().' '.site_phrase('students'); ?>
                        </span>
                        |
                        <span class="text-dark fw-800 text-14px text-muted">
                            <?php echo $this->crud_model->get_instructor_wise_courses($instructor_details['id'])->num_rows().' '.site_phrase('courses'); ?>
                        </span>
                      </div>
                    </div>
                    <?php $skills = explode(',', $instructor_details['skills']); ?>
                    <?php foreach($skills as $skill): ?>
                      <span class="badge badge-sub-warning text-12px my-1 py-2"><?php echo $skill; ?></span>
                    <?php endforeach; ?>

                    
                    <div class="description">
                      <?php echo ellipsis(strip_tags($instructor_details['biography']), 180); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="student-feedback-box mt-5 pb-3">
          <div class="student-feedback-title">
            <?php echo site_phrase('student_feedback'); ?>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="average-rating ms-auto me-auto float-md-start mb-sm-4">
                <div class="num">
                  <?php
                  $total_rating =  $this->ebook_model->get_ratings($ebook_details['ebook_id'], true)->row()->rating;
                  $number_of_ratings = $this->ebook_model->get_ratings($ebook_details['ebook_id'])->num_rows();
                  if ($number_of_ratings > 0) {
                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                  } else {
                    $average_ceil_rating = 0;
                  }
                  echo $average_ceil_rating;
                  ?>
                </div>
                <div class="rating">
                  <?php for ($i = 1; $i < 6; $i++) : ?>
                    <?php if ($i <= $average_ceil_rating) : ?>
                      <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                    <?php else : ?>
                      <i class="fas fa-star" style="color: #abb0bb;"></i>
                    <?php endif; ?>
                  <?php endfor; ?>
                </div>
                <div class="title text-15px fw-700"><?php echo $number_of_ratings; ?> <?php echo site_phrase('reviews'); ?></div>
              </div>
              <div class="individual-rating">
                <ul>
                  <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <li>
                      <div>
                        <span class="rating">
                          <?php for ($j = 1; $j <= (5 - $i); $j++) : ?>
                            <i class="fas fa-star"></i>
                          <?php endfor; ?>
                          <?php for ($j = 1; $j <= $i; $j++) : ?>
                            <i class="fas fa-star filled"></i>
                          <?php endfor; ?>

                        </span>
                      </div>
                      <div class="progress ms-2 mt-1">
                        <div class="progress-bar" style="width: <?php echo $this->ebook_model->get_percentage_of_specific_rating($i, 'course', $ebook_id); ?>%"></div>
                      </div>
                      <span class="d-inline-block ps-2 text-15px fw-500">
                        (<?php echo $this->db->get_where('rating', array('ratable_type' => 'course', 'ratable_id' => $ebook_id, 'rating' => $i))->num_rows(); ?>)
                      </span>
                    </li>
                  <?php endfor; ?>
                </ul>
              </div>
            </div>
          </div>

          <div class="reviews mt-5">
            <h3><?php echo site_phrase('reviews'); ?></h3>
            <ul>
              <?php
              $ratings = $this->ebook_model->get_ratings($ebook_id)->result_array();
              foreach ($ratings as $rating) :
              ?>
                <li>
                  <div class="row">
                    <div class="col-auto">
                      <div class="reviewer-details clearfix">
                        <div class="reviewer-img">
                          <img src="<?php echo $this->user_model->get_user_image_url($rating['user_id']); ?>" alt="">
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <div class="review-time">
                        <div class="reviewer-name fw-500">
                          <?php
                          $user_details = $this->ebook_model->get_user($rating['user_id'])->row_array();
                          echo $user_details['first_name'] . ' ' . $user_details['last_name'];
                          ?>
                        </div>
                        <!-- <div class="time text-11px text-muted">
                          <?php echo date('d/m/Y', $rating['date_added']); ?>
                        </div> -->
                      </div>
                      <div class="review-details">
                        <div class="rating">
                          <?php
                          for ($i = 1; $i < 6; $i++) : ?>
                            <?php if ($i <= $rating['rating']) : ?>
                              <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                            <?php else : ?>
                              <i class="fas fa-star" style="color: #abb0bb;"></i>
                            <?php endif; ?>
                          <?php endfor; ?>
                        </div>
                        <div class="review-text text-13px">
                          <?php echo $rating['comment']; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-lg-4 order-first order-lg-last">
        <div class="course-sidebar natural">
          <?php if ($ebook_details['file'] != "") : ?>
            <div class="preview-video-box">
              <a data-bs-toggle="modal" data-bs-target="#CoursePreviewModal">
                <img src="<?php echo $url = $this->ebook_model->get_ebook_thumbnail_url($ebook_details['ebook_id']); ?>" alt="" class="w-100">
                
                <span class="play-btn"></span>
              </a>
            </div>
          <?php endif; ?>
          <div class="course-sidebar-text-box">
            <div class="price text-center">
              <?php if ($ebook_details['is_free'] == 1) : ?>
                <span class="current-price"><span class="current-price"><?php echo site_phrase('free'); ?></span></span>
              <?php else : ?>
                <?php if ($ebook_details['discount_flag'] == 1) : ?>
                  <span class="original-price"><?php echo currency($ebook_details['price']) ?></span>
                  <span class="current-price"><span class="current-price"><?php echo currency($ebook_details['discounted_price']); ?></span></span>
                  <input type="hidden" id="total_price_of_checking_out" value="<?php echo currency($ebook_details['discounted_price']); ?>">
                <?php else : ?>
                  <span class="current-price"><span class="current-price"><?php echo currency($ebook_details['price']); ?></span></span>
                  <input type="hidden" id="total_price_of_checking_out" value="<?php echo currency($ebook_details['price']); ?>">
                <?php endif; ?>
              <?php endif; ?>
            </div>

            <?php if (is_purchased($ebook_details['ebook_id'])) : ?>
              <div class="already_purchased">
                <a href="<?php echo site_url('home/my_courses'); ?>"><?php echo site_phrase('download'); ?></a>
              </div>
            <?php else : ?>

              <!-- WISHLIST BUTTON -->
    

              <?php if ($ebook_details['is_free'] == 1) : ?>
                <div class="buy-btns">
                  <?php if ($this->session->userdata('user_login') != 1) : ?>
                    <a href="javascript:;" class="btn btn-buy-now" onclick="handleEnrolledButton()"><?php echo site_phrase('get_enrolled'); ?></a>
                  <?php else : ?>
                    <a href="<?php echo site_url('home/get_enrolled_to_free_course/' . $ebook_details['id']); ?>" class="btn btn-buy-now"><?php echo site_phrase('get_enrolled'); ?></a>
                  <?php endif; ?>
                </div>
              <?php else : ?>
                <div class="buy-btns">
                 

                   <button class="btn btn-buy" type="button" id="course_<?php echo $ebook_details['ebook_id']; ?>" onclick="handleBuyNow(this)"><?php echo site_phrase('buy_now'); ?></button>
                </div>
              <?php endif; ?>
            <?php endif; ?>


         
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal -->
<?php if ($ebook_details['video_url'] != "") :
  $provider = "";
  $video_details = array();
  if ($ebook_details['course_overview_provider'] == "html5") {
    $provider = 'html5';
  } else {
    $video_details = $this->video_model->getVideoDetails($ebook_details['video_url']);
    $provider = $video_details['provider'];
  }
?>
  <div class="modal fade" id="CoursePreviewModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content course-preview-modal">
        <div class="modal-header">
          <h5 class="modal-title"><span><?php echo site_phrase('course_preview') ?>:</span><?php echo $ebook_details['title']; ?></h5>
          <button type="button" class="close" data-bs-dismiss="modal" onclick="pausePreview()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="course-preview-video-wrap">
            <div class="embed-responsive embed-responsive-16by9">
              <?php if (strtolower(strtolower($provider)) == 'youtube') : ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">

                <div class="plyr__video-embed" id="player">
                  <iframe height="500" src="<?php echo $ebook_details['video_url']; ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>

                <script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
                <script>
                  const player = new Plyr('#player');
                </script>
                <!------------- PLYR.IO ------------>
              <?php elseif (strtolower($provider) == 'vimeo') : ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">
                <div class="plyr__video-embed" id="player">
                  <iframe height="500" src="https://player.vimeo.com/video/<?php echo $video_details['video_id']; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>

                <script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
                <script>
                  const player = new Plyr('#player');
                </script>
                <!------------- PLYR.IO ------------>
              <?php else : ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">
                <video poster="<?php echo $this->crud_model->get_course_thumbnail_url($ebook_details['id']); ?>" id="player" playsinline controls>
                  <?php if (get_video_extension($ebook_details['video_url']) == 'mp4') : ?>
                    <source src="<?php echo $ebook_details['video_url']; ?>" type="video/mp4">
                  <?php elseif (get_video_extension($ebook_details['video_url']) == 'webm') : ?>
                    <source src="<?php echo $ebook_details['video_url']; ?>" type="video/webm">
                  <?php else : ?>
                    <h4><?php site_phrase('video_url_is_not_supported'); ?></h4>
                  <?php endif; ?>
                </video>

                <style media="screen">
                  .plyr__video-wrapper {
                    height: 450px;
                  }
                </style>

                <script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
                <script>
                  const player = new Plyr('#player');
                </script>
                <!------------- PLYR.IO ------------>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
<!-- Modal -->

<style media="screen">
  .embed-responsive-16by9::before {
    padding-top: 0px;
  }
</style>
<script type="text/javascript">
  function handleCartItems(elem) {
    url1 = '<?php echo site_url('home/handleCartItems'); ?>';
    url2 = '<?php echo site_url('home/refreshWishList'); ?>';
    $.ajax({
      url: url1,
      type: 'POST',
      data: {
        course_id: elem.id
      },
      success: function(response) {
        $('#cart_items').html(response);
        if ($(elem).hasClass('active')) {
          $(elem).removeClass('active')
          $(elem).text("<?php echo site_phrase('add_to_cart'); ?>");
        } else {
          $(elem).addClass('active');
          $(elem).addClass('active');
          $(elem).text("<?php echo site_phrase('added_to_cart'); ?>");
        }
        $.ajax({
          url: url2,
          type: 'POST',
          success: function(response) {
            $('#wishlist_items').html(response);
          }
        });
      }
    });
  }

  function handleBuyNow(elem) {

    url1 = '<?php echo site_url('home/handleCartItemForBuyNowButton'); ?>';
    url2 = '<?php echo site_url('home/refreshWishList'); ?>';
    urlToRedirect = '<?php echo site_url('home/shopping_cart'); ?>';
    var explodedArray = elem.id.split("_");
    var course_id = explodedArray[1];

    $.ajax({
      url: url1,
      type: 'POST',
      data: {
        course_id: course_id
      },
      success: function(response) {
        $('#cart_items').html(response);
        $.ajax({
          url: url2,
          type: 'POST',
          success: function(response) {
            $('#wishlist_items').html(response);
            toastr.success('<?php echo site_phrase('please_wait') . '....'; ?>');
            setTimeout(
              function() {
                window.location.replace(urlToRedirect);
              }, 1000);
          }
        });
      }
    });
  }

  function handleEnrolledButton() {
    $.ajax({
      url: '<?php echo site_url('home/isLoggedIn?url_history='.base64_encode(current_url())); ?>',
      success: function(response) {
        if (!response) {
          window.location.replace("<?php echo site_url('login'); ?>");
        }
      }
    });
  }

  function handleAddToWishlist(elem) {
    $.ajax({
      url: '<?php echo site_url('home/isLoggedIn?url_history='.base64_encode(current_url())); ?>',
      success: function(response) {
        if (!response) {
          window.location.replace("<?php echo site_url('login'); ?>");
        }else{
          $.ajax({
            url: '<?php echo site_url('home/handleWishList'); ?>',
            type: 'POST',
            data: {
              course_id: elem.id
            },
            success: function(response) {
              if ($(elem).hasClass('active')) {
                $(elem).removeClass('active');
                $(elem).text("<?php echo site_phrase('add_to_wishlist'); ?>");
              } else {
                $(elem).addClass('active');
                $(elem).text("<?php echo site_phrase('added_to_wishlist'); ?>");
              }
              $('#wishlist_items').html(response);
            }
          });
        }
      }
    });
  }

  function pausePreview() {
    player.pause();
  }

  $('.course-compare').click(function(e) {
    e.preventDefault()
    var redirect_to = $(this).attr('redirect_to');
    window.location.replace(redirect_to);
  });

  function go_course_playing_page(course_id, lesson_id){
    var course_playing_url = "<?php echo site_url('home/lesson/'.slugify($ebook_details['title'])); ?>/"+course_id+'/'+lesson_id;

    $.ajax({
      url: '<?php echo site_url('home/go_course_playing_page/'); ?>'+course_id,
      type: 'POST',
      success: function(response) {
        if(response == 1){
          window.location.replace(course_playing_url);
        }
      }
    });
  }
</script>