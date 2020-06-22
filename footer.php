
    <footer class="gray rockbottom">
        &copy; 2020 Simple Blog &nbsp;&nbsp;&bull;&nbsp;&nbsp; Alle rechten voorbehouden
    </footer>

    <div class="dimmer"></div>

    <?php $themeBase = get_template_directory_uri(); ?>

    <script src="<?php echo $themeBase; ?>/js/nav.js"></script>
    <script src="<?php echo $themeBase; ?>/js/offcanvas.js"></script>
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

            let clearCommentForm = () => {
                let commentForm = document.getElementById('comment-form');
                let form = commentForm.querySelector('form');
                for (let input of form.elements) {
                    if (input.tagName.toLocaleLowerCase() == "textarea" || input.type == "text" || input.type == "email" || input.type == "url") {
                        input.value = "";
                    }
                }
            }

            let placeComment = (anchor) => {
                let el = anchor;
                while (el && !el.hasAttribute('data-comment-id')) {
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
                        commentForm.querySelector('[name="comment_parent"]').value = el.getAttribute('data-comment-id');
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
    </script>
</body>
</html>
