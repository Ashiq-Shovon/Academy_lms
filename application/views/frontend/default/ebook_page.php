<?php
isset($layout) ? "" : $layout = "list";
isset($selected_category_id) ? "" : $selected_category_id = "all";
isset($selected_rating) ? "" : $selected_rating = "all";
isset($selected_price) ? "" : $selected_price = "all";
// echo $selected_category_id.'-'.$selected_level.'-'.$selected_language.'-'.$selected_rating.'-'.$selected_price;
$number_of_visible_categories = 10;

?>

<section class="category-header-area" style="background-image: url('<?php echo base_url('uploads/system/course_page_banner.png'); ?>');">
    <div class="image-placeholder-1"></div>
    <div class="container-lg breadcrumb-container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item display-6 fw-bold">
                <a href="<?php echo site_url('home/courses'); ?>">
                    <?php echo site_phrase('all_courses'); ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-light display-6 fw-bold">
                <?php
                if ($selected_category_id == "all") {
                    echo site_phrase('all_category');
                } else {
                    $category_details = $this->crud_model->get_category_details_by_id($selected_category_id)->row_array();
                    echo $category_details['name'];
                }
                ?>
            </li>
          </ol>
        </nav>
    </div>
</section>


<section class="category-course-list-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 filter-area">
                <div class="card border-0 radius-10">
                    <div id="collapseFilter" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body p-0">
                            <div class="filter_type px-4 pt-4">
                                <h5 class="fw-700 mb-4"><?php echo site_phrase('categories'); ?></h5>
                                <ul>
                                    <li class="">
                                        <div class="text-15px fw-700 d-flex">
                                            <input type="radio" id="category_all" name="sub_category" class="categories custom-radio" value="all" onclick="filter(this)" <?php if ($selected_category_id == 'all') echo 'checked'; ?>>
                                            <label for="category_all"><?php echo site_phrase('all_category'); ?></label>
                                            <div class="ms-auto">(<?php echo $this->crud_model->get_active_course()->num_rows(); ?>)</div>
                                        </div>
                                    </li>
                                    <?php
                                    $counter = 1;
                                    $total_number_of_categories = $this->db->get('ebook_category')->num_rows();
                                    $categories = $this->ebook_model->get_categories()->result_array();
                
                                    foreach ($categories as $category) : ?>
                                        <li class="mt-3">
                                            <div class="text-15px fw-700 d-flex <?php if ($counter > $number_of_visible_categories) : ?> hidden-categories hidden <?php endif; ?>">
                                                <input type="radio" id="category-<?php echo $category['category_id']; ?>" name="sub_category" class="categories custom-radio" value="<?php echo $category['slug']; ?>" onclick="filter(this)" <?php if ($selected_category_id == $category['category_id']) echo 'checked'; ?>>
                                                <label for="category-<?php echo $category['category_id']; ?>"><?php echo $category['slug']; ?></label>
                                                <div class="ms-auto">(<?php echo $this->ebook_model->get_active_addon_by_category_id($category['category_id'], 'category_id')->num_rows(); ?>)</div>
                                            </div>
                                        </li>
                                        
                                    <?php endforeach; ?>
                                </ul>
                                <a href="javascript:;" class="text-13px fw-500" id="city-toggle-btn" onclick="showToggle(this, 'hidden-categories')"><?php echo $total_number_of_categories > $number_of_visible_categories ? site_phrase('show_more') : ""; ?></a>
                            </div>
                            <hr>
                            <div class="filter_type px-4">
                                <div class="form-group">
                                    <h5 class="fw-700 mb-3"><?php echo site_phrase('price'); ?></h5>
                                    <ul>
                                        <li>
                                            <div class="">
                                                <input type="radio" id="price_all" name="price" class="prices custom-radio" value="all" onclick="filter(this)" <?php if ($selected_price == 'all') echo 'checked'; ?>>
                                                <label for="price_all"><?php echo site_phrase('all'); ?></label>
                                            </div>
                                            <div class="">
                                                <input type="radio" id="price_free" name="price" class="prices custom-radio" value="free" onclick="filter(this)" <?php if ($selected_price == 'free') echo 'checked'; ?>>
                                                <label for="price_free"><?php echo site_phrase('free'); ?></label>
                                            </div>
                                            <div class="">
                                                <input type="radio" id="price_paid" name="price" class="prices custom-radio" value="paid" onclick="filter(this)" <?php if ($selected_price == 'paid') echo 'checked'; ?>>
                                                <label for="price_paid"><?php echo site_phrase('paid'); ?></label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <hr>
                            <div class="filter_type px-4">
                                <h5 class="fw-700 mb-3"><?php echo site_phrase('ratings'); ?></h5>
                                <ul>
                                    <li>
                                        <div class="">
                                            <input type="radio" id="all_rating" name="rating" class="ratings custom-radio" value="<?php echo 'all'; ?>" onclick="filter(this)" <?php if ($selected_rating == "all") echo 'checked'; ?>>
                                            <label for="all_rating"><?php echo site_phrase('all'); ?></label>
                                        </div>
                                    </li>
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <li>
                                            <div class="">
                                                <input type="radio" id="rating_<?php echo $i; ?>" name="rating" class="ratings custom-radio" value="<?php echo $i; ?>" onclick="filter(this)" <?php if ($selected_rating == $i) echo 'checked'; ?>>
                                                <label for="rating_<?php echo $i; ?>">
                                                    <?php for ($j = 1; $j <= $i; $j++) : ?>
                                                        <i class="fas fa-star" style="color: #f4c150;"></i>
                                                    <?php endfor; ?>
                                                    <?php for ($j = $i; $j < 5; $j++) : ?>
                                                        <i class="far fa-star" style="color: #dedfe0;"></i>
                                                    <?php endfor; ?>
                                                </label>
                                            </div>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row category-filter-box mx-0" >
                    <div class="col-md-6">
                        <button class="btn py-1 px-2 mx-2 <?php if($this->session->userdata('layout') == 'grid'){echo 'btn-danger';}else{echo 'btn-light'; } ?>" onclick="toggleLayout('grid')"><i class="fas fa-th-large"></i></button>
                        <button class="btn py-1 px-2 mx-2 <?php if($this->session->userdata('layout') == 'list'){echo 'btn-danger';}else{echo 'btn-light'; } ?>" onclick="toggleLayout('list')"><i class="fas fa-list"></i></button>
                        <!-- <span class="text-12px fw-700 text-muted"><?php echo site_phrase('showing').' '.count($ebooks).' '.site_phrase('of').' '.$total_result.' '.site_phrase('results'); ?></span> -->
                        
                    </div>
                    <div class="col-md-6 text-end filter-sort-by">
                    <form action="<?php echo site_url('ebook') ?>" method='get'>

                        <div class="input-group">
                            <input type="text" name="search" value="<?php echo (isset($search_value))?  $search_value : "" ?>" class="form-control rounded search" placeholder="Search" aria-label="Search"
                            aria-describedby="search-addon" />
                            <button type="submit" class="btn btn-outline-primary">search</button>
                        
                       </div>
                  
                    </form>
                    </div>

                </div>
                <div class="category-course-list">
                    <?php include 'category_wise_ebook_' . $layout . '_layout.php'; ?>
                    <?php if (count($ebooks) == 0) : ?>
                        <?php echo site_phrase('no_result_found'); ?>
                    <?php endif; ?>
                </div>
                <nav>
                    <?php if ($selected_category_id == "all" && $selected_price == 0 && $selected_rating == 'all') {
                        echo $this->pagination->create_links();
                    } ?>
                </nav>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function get_url() {
        var urlPrefix = '<?php echo site_url('ebook?'); ?>'
        var urlSuffix = "";
        var slectedCategory = "";
        var selectedPrice = "";
        var selectedRating = "";
        var search_text = "";

        // Get selected category
        $('.categories:checked').each(function() {
            slectedCategory = $(this).attr('value');
        });

        // Get selected price
        $('.prices:checked').each(function() {
            selectedPrice = $(this).attr('value');
        });

      
        
        searchText = $('.search').val();
       


        // Get selected rating
        $('.ratings:checked').each(function() {
            selectedRating = $(this).attr('value');
        });

       
    
        if(searchText != null){
            urlSuffix = "category=" + slectedCategory + "&&price=" + selectedPrice  + "&&rating=" + selectedRating + "&&search=" + searchText;

        }
        else{
            urlSuffix = "category=" + slectedCategory + "&&price=" + selectedPrice  + "&&rating=" + selectedRating ;

        }
        var url = urlPrefix + urlSuffix;
        return url;
    }

    function filter() {
        var url = get_url();
        window.location.replace(url);
        //console.log(url);
    }

    function toggleLayout(layout) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('home/set_layout_to_session'); ?>',
            data: {
                layout: layout
            },
            success: function(response) {
                location.reload();
            }
        });
    }

    function showToggle(elem, selector) {
        $('.' + selector).slideToggle(20);
        if ($(elem).text() === "<?php echo site_phrase('show_more'); ?>") {
            $(elem).text('<?php echo site_phrase('show_less'); ?>');
        } else {
            $(elem).text('<?php echo site_phrase('show_more'); ?>');
        }
    }

    $('.course-compare').click(function(e) {
        e.preventDefault()
        var redirect_to = $(this).attr('redirect_to');
        window.location.replace(redirect_to);
    });


</script>