const Nav = {
    Accordion: element => {
        element.addEventListener("click", event => {
            if (event.target.tagName.toLowerCase() == "li" && event.target.classList.contains("node")) {
                event.preventDefault();
                event.target.classList.toggle("open");
                let ul = event.target.querySelector("ul");
                if (ul) {
                    let currHeight = ul.offsetHeight;
                    ul.style.height = `${currHeight}px`;
                    ul.classList.add("animated");
                    setTimeout(() => {
                        ul.style.height = `${currHeight == 0 ? ul.scrollHeight: 0}px`;
                        setTimeout(() => {
                            ul.classList.remove("animated");
                            if (currHeight == 0) {
                                ul.style.height = 'auto';
                            }
                        }, 300);
                    }, 50);
                }
            }
        });
    },
    Drilldown: element => {
        if (!element) return;

        let currLi;
        let backButton;
        let navTitle;

        {
            let navNav = document.createElement("div");
            navNav.className = "nav-nav";
            let navContent = document.createElement("div");
            navContent.className = "nav-content";
            let ul = element.querySelector("ul");
            element.insertBefore(navNav, ul);
            element.insertBefore(navContent, ul);
            navContent.appendChild(ul);

            backButton = document.createElement("button");
            backButton.className = "nav-back";
            backButton.disabled = true;
            navTitle = document.createElement("div");
            navTitle.className = "nav-title";
            navTitle.innerHTML = "Menu";
            navNav.appendChild(backButton);
            navNav.appendChild(navTitle);
        }

        element.addEventListener("click", event => {
            let t = event.target;
            if (t.tagName.toLowerCase() == "button" && t.classList.contains("nav-back")) {
                if (currLi) {
                    currLi.classList.remove("open");
                    currLi = currLi.parentElement.parentElement;
                    if (currLi.tagName.toLowerCase() != "li") currLi = null;
                    if (!currLi) {
                        backButton.disabled = true;
                        navTitle.innerHTML = "Menu";
                    } else {
                        navTitle.innerHTML = currLi.querySelector("a").innerHTML;
                    }
                }
            } else if (t.tagName.toLowerCase() == "a" && t.parentElement.classList.contains("node")) {
                event.preventDefault();
                let li = t.parentElement;
                let title = t.innerHTML;
                li.classList.toggle("open");
                currLi = li;

                backButton.disabled = false;
                navTitle.innerHTML = title;
            }
        });
    }
}
