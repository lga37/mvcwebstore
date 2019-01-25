<?php
if(!isset($produto) || !is_array($produto)){
    msg("Nenhum registro/produto encontrado","");die;
}
#var_dump($produto);
extract($produto);
?>
	<div class="row">
	    <div class="col-md-12 col-sm-12">

	        <br>

	        <div class="row">
            <div class="col-sm-6"><!-- 1 coluna -->
				<div class="carousel slide" id="carousel-detalhes" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#carousel-detalhes" data-slide-to="0" class="active"></li>
						<li data-target="#carousel-detalhes" data-slide-to="1"></li>
						<li data-target="#carousel-detalhes" data-slide-to="2"></li>
					</ol>
					<div class="carousel-inner" role="listbox">
						<div class="carousel-item active">
							<img class="img-responsive" src="//lorempixel.com/800/400/sports/1/" alt="">
							<div class="carousel-caption">
								<h2>Example headline.</h2>
							</div>
						</div>
						<div class="carousel-item">
							<img class="img-responsive" src="//lorempixel.com/800/400/sports/2/" alt="">
							<div class="carousel-caption">
								<h2>Example headline.</h2>
							</div>
						</div>
						<div class="carousel-item">
							<img class="img-responsive" src="//lorempixel.com/800/400/sports/3/" alt="">
						</div>
					</div>
					<a class="left carousel-control" href="#carousel-detalhes" role="button" data-slide="prev">
						<span class="icon-prev" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-detalhes" role="button" data-slide="next">
						<span class="icon-next" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div><!-- .carousel -->
            </div><!-- .col-sm-6 -->

            <div class="col-sm-6"><!-- 2 coluna -->
              <div class="card">
                <div class="card-block">
                  <h4 class="card-title"><?=$nome?></h4>
                  <p class="card-text">
                   Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                   tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                   quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                   consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                   cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                   proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

                  </p>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">R$ <?=$preco?></li>
                  <li class="list-group-item">Prazo:<?=mt_rand(0,20);?> dias</li>
                  <li class="list-group-item">Peso:<?=$peso?> Kg</li>
                  <li class="list-group-item">Estoque:<?=$estoque?> Unids</li>
                </ul>
                <div class="card-block">

		            <a href="#" class="card-link"><i class="fa fa-2x fa-heart"></i></a>
		            <a href="<?=URL?>?c=carrinho&a=add&id=<?=$id?>" class="card-link"><i class="fa fa-2x fa-shopping-cart"></i></a>
		            <a href="#" class="card-link"><i class="fa fa-2x fa-share-alt"></i></a>


                </div>
              </div>

            </div><!-- col-sm-6 -->
          </div><!-- .row -->
		
		<br>


	    </div><!-- col-md-12 -->
	</div><!-- row -->
