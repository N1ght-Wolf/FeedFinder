<div id="sidebar" class="sidebar collapsed sidebar-left">
    <!-- Nav tabs -->
    <div class="sidebar-tabs">
        <ul role="tablist" id='list'>
            <li><a href="#home" role="tab" title="This is some text I want to display."><i class="fa fa-bars"></i></a>
            </li>
            <li><a href="#people" role="tab" title="show something about users"><i class="fa fa-users"></i></a></li>
            <!-- <li><a href="#review" role="tab" title="show something about reviews"><i class="fa fa-comments"></i></a></li> -->
            <li><a id='venues-icon' href="#places" role="tab" title="show something about venues"><i
                        class="fa fa-map-marker"></i></a></li>
            <li><a id='review-icon' href="#reviews" role="tab" title="show something about venues"><i
                        class="fa fa-comment"></i></a></li>
            <li><a id='breastfeeding-friendliness' href="#breastfeeding-friendliness" role="tab"
                   title="show something about venues"><i
                        class="fa fa-smile-o"></i></a></li>

        </ul>

        <!-- <ul role="tablist">
            <li><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
        </ul> -->
    </div>

    <!-- Tab panes -->
    <div class="sidebar-content">
        <div class="sidebar-pane" id="home">
            <h1 class="sidebar-header">
                Search Control
                <div class="sidebar-close"><i class="fa fa-caret-left"></i></div>
            </h1>

            <p></p>
        </div>

        <div class="sidebar-pane" id="people">
            <h1 class="sidebar-header"> People
                <div class="sidebar-close">
                    <i class="fa fa-caret-left"></i>
                </div>
            </h1>
            <p>Find out how many people are using Feedfinder in the last</p>
            <?php echo $this->element('sidebar-people-form-element'); ?>
        </div>


        <div class="sidebar-pane" id="places">
            <h1 class="sidebar-header">Places
                <div class="sidebar-close">
                    <i class="fa fa-caret-left"></i>
                </div>
            </h1>
            <p>Find out how many places mapped on Feedfinder in the last</p>
            <?php echo $this->element('sidebar-places-form-element'); ?>
        </div>
        <!-- start of reviews tab   -->
        <div class="sidebar-pane" id="reviews">
            <h1 class="sidebar-header">Reviews
                <div class="sidebar-close">
                    <i class="fa fa-caret-left"></i>
                </div>
            </h1>
            <p>Find how many reviews have been added to Feedfinder in the last</p>
            <?php echo $this->element('sidebar-review-form-element'); ?>
        </div>
        <!--  end of reviews tab      -->

        <!-- start of reviews tab   -->
        <div class="sidebar-pane" id="breastfeeding-friendliness">
            <h1 class="sidebar-header">Breastfeeding friendliness
                <div class="sidebar-close">
                    <i class="fa fa-caret-left"></i>
                </div>
            </h1>
            <p>Find out the breasfeeding friendliness reported by feedfinder community in the last</p>
            <?php echo $this->element('sidebar-friendliness-form-element'); ?>
        </div>
        <!--  end of reviews tab      -->


        <div class="sidebar-pane" id="settings">
            <h1 class="sidebar-header">Settings
                <div class="sidebar-close"><i class="fa fa-caret-left"></i></div>
            </h1>
        </div>
    </div>
</div>
