
<!--
<div class="modal fade" id="staticBackdrop" tabindex="-1" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="staticBackdropLabel">ADD CATEGORY</h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1 text-white"></span></button>
        </div>
        <div class="col-md-12">
          <div class="col-12 col-sm-6 col-xl-12">
            <div class="mb-4">
            <h5 class="m-2  text-1000">Name</h5>
            <input id="name" class="form-control m-0" type="text" placeholder="Enter category name" />
            </div>
          </div>
          <div class="col-12 col-sm-6 col-xl-12">
            <div class="mb-4">
            <h5 class="mb-2 text-1000">Slug</h5>
            <input id="slug" class="form-control mb-xl-3" type="text" placeholder="Enter category slug" />
            </div>
          </div>
          <div class="col-12 col-sm-6 col-xl-12">
            <div class="mb-4">
            <h5 class="mb-2 text-1000">Image</h5>
            <input id="image" class="form-control mb-xl-3" type="file" placeholder="Enter image URL" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="button" onclick="createcategory()">Create category</button>
          <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
</div>
-->

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addDealModal" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-100 p-6">
      <div class="modal-header border-0 p-0 mb-2">
        <h3 class="mb-0">create category</h3>
        <button class="btn btn-sm btn-phoenix-secondary" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times text-danger"></span></button>
      </div>
      <div class="modal-body px-0">
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="mb-4">
              <label class="text-1000 fw-bold mb-2">Categories Name</label>
              <input class="form-control" id="name" type="text" placeholder="Enter Categories name" />
            </div>
          </div>
          <div class="col-lg-6">
            <div class="mb-4">
              <label class="text-1000 fw-bold mb-2">Categories Slug</label>
              <input id="slug" class="form-control" type="text" placeholder="Enter Categories slug" />
            </div>
          </div>
          <div class="col-lg-12">
            <div class="mb-4">
              <label class="text-1000 fw-bold mb-2">Categories images</label>
              <input id="image" class="form-control" type="file" placeholder="Enter Categories slug" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-6 px-0 pb-0">
        <button class="btn btn-link text-danger px-3 my-0" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        <button class="btn btn-primary my-0" type="button" onclick="createcategory()">Create Categories</button>
      </div>
    </div>
  </div>
</div>

  <script>
    function createcategory() {
        const formData = {
        name: getValue('name'),
        slug: getValue('slug'),
        image: getValue('image'),
        };
  
        fetch('/api/admin/category', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken(),
        },
        body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
        if (data.message === 'Category successfully registered') {
            alert('Category successfully registered');
            //redirectToLogin(); 
        } else {
            handleErrors(data.errors);
        }
        })
        .catch(error => console.error('Error:', error));
    }
  
    function getValue(elementId) {
    return document.getElementById(elementId).value;
    }
  
    function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }
  
    function handleErrors(errors) {
    Object.keys(errors).forEach(key => {
        const errorMessage = errors[key];
        // Assuming you have elements with ids like `${key}-error`
        document.getElementById(`${key}-error`).innerText = errorMessage;
    });
    }
  
    function redirectToLogin() {
    window.location.href = '/admin';
    }
  </script>