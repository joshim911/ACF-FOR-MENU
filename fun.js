const items = document.getElementsByClassName( 'menu-item' );



for (let i = 0; i < items.length; i++) {
    
    for (let ii = 0; ii < items[i].classList.length; ii++) {
    
        if( items[i].classList[ii] == 'menu-item-has-children') {
            setDownArro(i);
        }else{
            console.log('no menu has child');
            
        }
    
    }
    
}



function setDownArro( index )
{
    let aTag = items[index].children[0];
    let aTagText = aTag.innerHTML;
    const arroIcon = document.createElement("i");
    // arroIcon.innerHTML = "&#x21e9;";
   
    arroIcon.setAttribute( 'class', 'fa-solid fa-angle-down' );
    aTagText.innerHTML = aTagText + arroIcon;
    aTag.appendChild(arroIcon);
    console.log(aTag);
}