//$(<selector>).method([parameters]);
/*
shortcut
$(function() {



});
 */
$(document).ready(function() {
    
    // Change the cursor to a pointer (hand) when user hovers over div's h3
    //   Note: this could have been done more efficiently by just adding cursor: pointer;
    //         to the div.bookGenre h3 selector in bookLister.css
    $('div.bookGenre h3:first-child').css({
        
        cursor: 'pointer'
        
    }).click(function() {
        
        $(this).next().slideToggle('slow', 'easeOutBounce');
        
    });
    
    // hide all the book category div's when the page first loads
    $('div.bookGenre > blockquote').hide();
    
    // display first div by default
    $('div.bookGenre:first > blockquote').show();
    
    // when user clicks on a category <h3>, animate
    // the corresponding div's blockquote to open/close
    // depending on its current state (toggle)
    $()
    
});