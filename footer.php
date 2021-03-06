
    <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')): ?>
        <footer class="primary footer">
            <div class="large container">
                <div class="columns">
                <?php if (is_active_sidebar('footer-1')): ?>
                    <div class="column">
                        <div class="brand"><?php echo get_theme_mod('simpleblog_brand_text', get_bloginfo('name')); ?></div>
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>
                <?php if (is_active_sidebar('footer-2')): ?>
                    <div class="narrow column">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>
                <?php if (is_active_sidebar('footer-3')): ?>
                    <div class="narrow column">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php endif; ?>
                <?php if (is_active_sidebar('footer-4')): ?>
                    <div class="column">
                        <?php dynamic_sidebar('footer-4'); ?>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <footer class="gray rockbottom">
        &copy; 2020 <?php bloginfo('name'); ?> &nbsp;&nbsp;&bull;&nbsp;&nbsp; <?php echo __('Alle rechten voorbehouden'); ?>
    </footer>

    <div class="dimmer"></div>

    <?php $themeBase = get_template_directory_uri(); ?>

    <script src="<?php echo $themeBase; ?>/js/nav.js"></script>
    <script src="<?php echo $themeBase; ?>/js/offcanvas.js"></script>
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/typewriter.css"/>
    <script src="<?php echo $themeBase; ?>/js/typewriter.js"></script>
    <script>
        Nav.Drilldown(document.querySelector(".nav-drilldown"));

        {
            let lastScrollTop = 0;
            let topbar = document.querySelector(".topbar");
            let onScroll = event => {
                let scrollTop = window.pageYOffset;
                if (scrollTop > 100 && lastScrollTop <= 100) {
                    topbar.classList.add("scrolled");
                } else if (scrollTop <= 100 && lastScrollTop > 100) {
                    topbar.classList.remove("scrolled");
                }
                lastScrollTop = scrollTop;
            }
            window.addEventListener('scroll', onScroll);
            onScroll();
        }

        {
            let hamburger = document.querySelector(".hamburger");
            if (hamburger) {
                hamburger.addEventListener("click", event => {
                    event.preventDefault();
                    hamburger.classList.toggle('open');
                });
            }
        }

        {
            let currAnchor = null;
            let postId = null;

            let clearCommentForm = () => {
                let commentForm = document.getElementById('comment-form');
                let form = commentForm.querySelector('form');
                if (!form) return;
                for (let input of form.elements) {
                    if (input.tagName.toLocaleLowerCase() == "textarea" || input.type == "text" || input.type == "email" || input.type == "url") {
                        input.value = "";
                    }
                }
            }

            let placeComment = (anchor) => {
                let el = anchor;
                while (el && !(el.hasAttribute('data-comment-id') || el.classList.contains('comments'))) {
                    el = el.parentElement;
                }

                if (el) {
                    let content = el.querySelector('.content');
                    if (content) {
                        if (currAnchor) {
                            currAnchor.style.display = currAnchor.oldDisplay;
                        }
                        let commentForm = document.getElementById('comment-form');
                        clearCommentForm();
                        content.appendChild(commentForm);
                        if (el.hasAttribute('data-comment-id')) {
                            // if (!postId) postId = commentForm.querySelector('[name="comment_post_ID"]').value;
                            commentForm.querySelector('[name="comment_parent"]').value = el.getAttribute('data-comment-id');
                            // commentForm.querySelector('[name="comment_post_ID"]').value = "";
                        } else {
                            commentForm.querySelector('[name="comment_parent"]').value = 0;
                            // if (postId) {
                            //     commentForm.querySelector('[name="comment_post_ID"]').value = postId;
                            // }
                        }
                        commentForm.style.display = 'block';
                        anchor.oldDisplay = getComputedStyle(anchor).display;
                        anchor.style.display = "none";
                        currAnchor = anchor;

                        commentForm.querySelector('input,textarea').focus();
                    }
                }
            }

            let cancelComment = () => {
                if (currAnchor) {
                    currAnchor.style.display = currAnchor.oldDisplay;
                }
                let commentForm = document.getElementById('comment-form');
                commentForm.style.display = "none";
            }

            window.addEventListener("click", event => {
                if (event.target && event.target.hasAttribute('data-place-comment')) {
                    event.preventDefault();
                    event.stopPropagation();
                    placeComment(event.target);
                }
                else if (event.target && event.target.hasAttribute('data-cancel-comment')) {
                    event.preventDefault();
                    event.stopPropagation();
                    cancelComment();
                }
            });
        }

        {
            let alignfulls = document.querySelectorAll(".alignfull");
            window.addEventListener("load", event => {
                for (let alignfull of alignfulls) {
                    let alignwide = document.createElement("div");
                    alignwide.className = "alignwide";
                    let spacer = document.createElement("div");
                    spacer.style.height = `${alignfull.offsetHeight}px`;
                    alignfull.parentElement.insertBefore(alignwide, alignfull);
                    alignfull.parentElement.insertBefore(spacer, alignfull.nextSibling);
                    alignfull.afSpacer = spacer;
                    alignfull.afHeight = alignfull.offsetHeight;

                }
            });
            window.addEventListener("resize", event => {
                for (let alignfull of alignfulls) {
                    if (alignfull.afSpacer && alignfull.afHeight != alignfull.offsetHeight) {
                        alignfull.afSpacer.style.height = `${alignfull.offsetHeight}px`;
                        alignfull.afHeight = alignfull.offsetHeight;
                    }
                }
            });
        }

        {
            let twElement = document.getElementById('cover-typewriter');
            if (twElement) {
                tw(twElement.querySelector('ul'));
            }
        }

        {
            let embedWrappers = document.querySelectorAll('.wp-block-embed__wrapper');
            for (let wrapper of embedWrappers) {
                let iframe = wrapper.querySelector('iframe');
                if (iframe) {
                    let ratio = parseFloat(iframe.getAttribute('height')) / parseFloat(iframe.getAttribute('width'));
                    let ratioEl = document.createElement('div');
                    ratioEl.className = 'ratio';
                    ratioEl.style.paddingTop = `${100 * ratio}%`;
                    wrapper.appendChild(ratioEl);
                    iframe.removeAttribute('width');
                    iframe.removeAttribute('height');
                    wrapper.classList.add('fixed');
                }
            }
        }
    </script>
</body>
</html>
