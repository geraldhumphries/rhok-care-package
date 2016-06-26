(function () {
    init();

    function init () {
        var products = document.querySelectorAll('#pkgitems .product');
        var dropzones = document.querySelectorAll('.dropzone');
        var productTable = document.querySelector('#packageItemsList tbody');

        products.forEach(function (product) {
            product.addEventListener('dragstart', handleDragProduct, false);
            product.addEventListener('dragend', handleDragProductEnd, false);
        });
        dropzones.forEach(function (dropzone) {
            dropzone.addEventListener('drop', handleDropProduct, false);

            // drop events won't fire without canceling dragenter and dragover
            // thanks javascript
            dropzone.addEventListener('dragenter', cancelEvent, false);
            dropzone.addEventListener('dragover', cancelEvent, false);
        });
        productTable.addEventListener('click', function (event) {
            if (event.target.classList.contains('removeProduct')) {
                handleRemoveProduct(event.target);
            }
        });


// retrieve the element
element = document.getElementById("careBoxx");

// reset the transition by...
element.addEventListener(addProduct, function(e) {
  e.preventDefault;
  
  // -> removing the class
  element.classList.remove("shakeit");
  
  // -> triggering reflow /* The actual magic */
  // without this it wouldn't work. Try uncommenting the line and the transition won't be retriggered.
  element.offsetWidth = element.offsetWidth;
  
  // -> and re-adding the class
  element.classList.add("shakeit");
}, false);


    }
    function cancelEvent (event) {
        event.preventDefault();
    }

    function addProduct (productData) {
        var productTable = document.querySelector('#packageItemsList tbody');
        var productRow = productTable.querySelector('tr[data-product-id="' + productData.productId + '"]');
        if (productRow) {
            productRow.dataset.quantity++;
            productRow.cells[2].innerHTML = productRow.dataset.quantity;
        } else {
            productRow = productTable.insertRow(0);
            productRow.insertCell(0).innerHTML = productData.productName;
            productRow.insertCell(1).innerHTML = productData.price;
            productRow.insertCell(2).innerHTML = 1;
            var deleteCell = productRow.insertCell(3);
            deleteCell.innerHTML = 'X';
            deleteCell.classList.add('removeProduct');
            productRow.dataset.productId = productData.productId;
            productRow.dataset.price = productData.price;
            productRow.dataset.quantity = 1;
        }

        //animation upon adding item
        element = document.getElementById("careBoxx");
        element.classList.remove("shakeit");  
        element.offsetWidth = element.offsetWidth;
        element.classList.add("shakeit");
    }
    function handleRemoveProduct (targetElement) {
        var row = targetElement.parentNode;
        row.parentNode.removeChild(row);
    }
    function handleDragProductEnd (event) {
        this.style.opacity = '1';
    }
    function handleDragProduct (event) {
        this.style.opacity = '0.4';

        event.dataTransfer.effectAllowed = 'copy';
        event.dataTransfer.setData('dataset', JSON.stringify(this.dataset));
    }
    function handleDropProduct (event) {
        event.stopPropagation(); // Stops some browsers from redirecting.
        event.preventDefault();

        var productData = JSON.parse(event.dataTransfer.getData('dataset'));
        addProduct(productData);
    }
})();