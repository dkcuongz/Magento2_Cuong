document.addEventListener("DOMContentLoaded",function(){
    const items = document.querySelectorAll('ol > li');

    items.forEach(items=>item.addEventListener("click", function(){
        console.log(this);
    })
    );
});