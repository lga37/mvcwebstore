<br><br>
<div class="row">
    <div class="col-md-12">
        <div class="card card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Others/gradient1.jpg);">
            <div class="text-white text-center py-5 px-4 my-5">
                <div>
                    <h1 class="card-title pt-3 mb-5 font-bold"><strong>Bem Vindos a StoneCommerce</strong></h1>
                    <p class="mx-5 mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat fugiat,
                        laboriosam, voluptatem, optio vero odio nam sit officia accusamus
                        minus error nisi architecto nulla ipsum dignissimos. Odit sed qui,
                        dolorum!.</p>
                    <a class="btn btn-outline-white btn-rounded"><i class="fa fa-clone left"></i> View project</a>
                </div>
            </div>
        </div>
    </div>
</div>

<br><br>

<?php 
if(isset($produtos) && count($produtos)>0):
  vitrine($produtos);
else:
  echo "vitrine vazia";
endif;