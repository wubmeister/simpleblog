function tw(element) {
    if (!element) return;

    let items = element.querySelectorAll("li");
    if (items.length == 0) return;

    element.classList.add('tw');

    let itemIndex = -1;
    let charIndex = 0;
    let currSpans = null;
    let reverse = false;

    let convertItem = (item) => {
        let chars = item.innerText.split('')
           .map(ch => `<span${ch == ' ' ? ' class="sp"' : ''}>${ch == ' ' ? ' ' : ch}</span>`);
        item.innerHTML = chars.join('') + '<span class="cur"></span>';
    }

    let nextItem = () => {
        if (itemIndex >= 0) {
            items[itemIndex].style.display = 'none';
        }
        if (++itemIndex >= items.length) {
            itemIndex = 0;
        }
        currSpans = items[itemIndex].querySelectorAll('span:not(.cur)');
        for (let span of currSpans) {
            span.style.display = 'none';
        }
        charIndex = 0;
        reverse = false;
        items[itemIndex].style.display = 'inline-block';
    }

    let showChar = () => {
        if (charIndex >= currSpans.length) {
            if (charIndex >= currSpans.length + 10) {
                if (reverse) {
                    nextItem();
                } else {
                  reverse = true;
                  charIndex = 0;
                }
            } else {
                charIndex++;
            }
        } else {
            if (reverse) {
              currSpans[(currSpans.length - 1) - charIndex].style.display = 'none';
            } else {
              currSpans[charIndex].style.display = 'inline';

            }
            charIndex++;
        }
    }

    for (let item of items) {
        convertItem(item);
    }

    nextItem();
    setInterval(showChar, 100);
}
