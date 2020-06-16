{
    let triggers = document.querySelectorAll("[data-offcanvas-trigger]");
    let offcanvas;
    let dimmer;

    let onDimmerClick = event => {
        document.body.classList.remove("offcanvas-open");
        setTimeout(() => {
            dimmer.style.display = "none";
        }, 300);
    }

    let onClick = event => {
        event.preventDefault();
        if (!offcanvas) {
            offcanvas = document.querySelector(".offcanvas");
        }
        if (offcanvas) {
            if (!dimmer) {
                dimmer = document.createElement("div");
                dimmer.className = "offcanvas-dimmer";
                document.body.appendChild(dimmer);
                dimmer.addEventListener("click", onDimmerClick);
            }
            dimmer.style.display = "block";
            setTimeout(() => {
                document.body.classList.toggle("offcanvas-open");
            }, 100);
        }
    }

    for (let trigger of triggers) {
        trigger.addEventListener("click", onClick);
    }
}
