<div class="row">

    <?php foreach ($ebooks as $ebook) :
        $instructor_details = $this->user_model->get_all_user($ebook['user_id'])->row_array();
        ?>
        <div class="col-md-6 col-xl-4">
            <div class="course-box-wrap">
                <a onclick="$(location).attr('href', '<?php echo site_url('ebook/ebook_details/' . rawurlencode(slugify($ebook['title'])) . '/' . $ebook['ebook_id']); ?>');" href="javascript:;" class="has-popover">
                    <div class="course-box">
                        <div class="course-image">
                            <img src="<?php echo $this->ebook_model->get_ebook_thumbnail_url($ebook['ebook_id']); ?>" alt="" class="img-fluid">
                        </div>
                        <div class="course-details">
                            <h5 class="title"><?php echo $ebook['title']; ?></h5>
                            <div class="rating">
                                <?php
                        $total_rating =  $this->ebook_model->get_ratings( $ebook['ebook_id'], true)->row()->rating;
                        $number_of_ratings = $this->ebook_model->get_ratings( $ebook['ebook_id'])->num_rows();
                                if ($number_of_ratings > 0) {
                                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                                } else {
                                    $average_ceil_rating = 0;
                                }

                                for ($i = 1; $i < 6; $i++) : ?>
                                    <?php if ($i <= $average_ceil_rating) : ?>
                                        <i class="fas fa-star filled"></i>
                                    <?php else : ?>
                                        <i class="fas fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <div class="d-inline-block">
                                    <span class="text-dark ms-1 text-15px">(<?php echo $average_ceil_rating; ?>)</span>
                                    <span class="text-dark text-12px text-muted ms-2">(<?php echo $number_of_ratings.' '.site_phrase('reviews'); ?>)</span>
                                </div>
                            </div>
                            

                            

                            <hr class="divider-1">

                            <div class="d-block">
                                <div class="floating-user d-inline-block">
                                   
                                        <?php $user_details = $this->user_model->get_all_user($ebook['user_id'])->row_array(); ?>
                                        <img src="<?php echo $this->user_model->get_user_image_url($user_details['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>" onclick="event.stopPropagation(); $(location).attr('href', '<?php echo site_url('home/instructor_page/'.$user_details['id']); ?>');">
                                   
                                </div>



                                <?php if ($ebook['is_free'] == 1) : ?>
                                    <p class="price text-right d-inline-block float-end"><?php echo site_phrase('free'); ?></p>
                                <?php else : ?>
                                    <?php if ($ebook['discount_flag'] == 1) : ?>
                                        <p class="price text-right d-inline-block float-end"><small><?php echo currency($ebook['price']); ?></small><?php echo currency($ebook['discounted_price']); ?></p>
                                    <?php else : ?>
                                        <p class="price text-right d-inline-block float-end"><?php echo currency($ebook['price']); ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>