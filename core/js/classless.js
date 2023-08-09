function addFontSize(addPx){
    html = document.querySelector('html');
    currentSize = parseFloat(window.getComputedStyle(html, null)
        .getPropertyValue('font-size'));
    html.style.fontSize = (currentSize + addPx) + 'px';
}

function toggleDarkMode(el){
    var theme='light'
    if (el.innerText == '☪'){
        el.innerText = '☀'; theme='dark';
    } else {
        el.innerText = '☪';
    }
    document.documentElement.setAttribute('data-theme', theme)
}