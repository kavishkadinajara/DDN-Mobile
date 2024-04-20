/***----------FoR LOGIN FORM----------***/

function forgotPassword() {
    document.getElementById('loginDiv').style.display = "none";
    document.getElementById('resetPWDiv').style.display = "block";
}

/***---------- FOR STORE ----------***/
var block = "block";
var none = "none";

/// Show Categories ///
function showCategories() {
    var showCategories = document.getElementById("category_select");
    if (showCategories.style.display === block) {
        showCategories.style.display = none;
    } else {
        showCategories.style.display = block;
    }
}



/// show Brands ///
function showBrands() {
    var showBrands = document.getElementById("brand_select");
    if (showBrands.style.display === block) {
        showBrands.style.display = none;
    } else {
        showBrands.style.display = block;
    }
}

///**************ITEM DETAILS PAGE**************/

// SHOW IMG WHEN CLICK//
    // Get references to the main image and the clickable images
    const mainImage = document.getElementById('main');
    const clickableImages = document.querySelectorAll('img[id^="1"], img[id^="2"], img[id^="3"], img[id^="4"]');

    // Add a click event listener to each clickable image
    clickableImages.forEach(image => {
        image.addEventListener('click', function() {
            // Set the source of the main image to the clicked image's source
            mainImage.src = this.src;
        });
    });



   






