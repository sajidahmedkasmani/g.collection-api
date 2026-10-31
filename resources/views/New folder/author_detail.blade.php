@extends('frontend_layout.master')
@section('page-title')
    {{ $author->title }}
@endsection
@section('main-content')
<main class="main-content">

		<!-- Arthor Detail -->
		<div class="single-aurthor-detail tc-padding">
			<div class="container">
				<div class="row">
					
					<!-- Aside -->
					<aside class="col-lg-4 col-md-5">
						<div class="arthor-detail-column">
							<div class="arthor-img">
                                @if ($author->author_img == 'No image found')
                                    <img src="/uploads/no-img.png" width="207" height="197" alt="No image found">
                                @else
                                    <img src="/uploads/{{ $author->author_img }}" width="207" height="197" alt="{{ $author->title }}">
                                @endif
							</div>
							<div class="arthor-detail">
								<h4>{{ $author->title }}</h4>
								<span>{{ $author->designation }}</span>
							</div>
							<div class="social-activity">
								<div>
									<ul class="social-icons">
					                	<li><a class="facebook" href="{{ $author->facebook_id }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
					                    <li><a class="twitter" href="{{ $author->twitter_id }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
					                    <li><a class="youtube" href="{{ $author->youtube_id }}" target="_blank"><i class="fa fa-youtube-play"></i></a></li>
					                    <li><a class="pinterest" href="{{ $author->pinterest_id }}" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>
					                </ul>
				                </div>
				         	</div>
						</div>
					</aside>
					<!-- Aside -->

					<!-- Content -->
					<div class="col-lg-8 col-md-7">
						<div class="single-arthor-detail">

							<!-- Widget -->
							<div class="single-arthor-widget">
								<h5>Author Overview</h5>
								<div class="author-overview">
									<p>{{ $author->description }}</p>
								</div>
							</div>
							<!-- Widget -->

							<!-- Widget -->
							<div class="single-arthor-widget">
								<h5>Recommended <span style="color:orange">{{ $author->title }}</span> Titles </h5>

								<!-- Recommended -->
							  	<div id="filter-masonry" class="gallery-masonry">
									@foreach($author->author_books as $key => $author_book)
									@if($key < 4)
									<!-- Product Box -->
					    			<div class="col-lg-3 col-xs-6 r-full-width masonry-grid most-recent">
					    				<div class="recommended-book">
					    					<div class="recommended-book-img">
												@if ($author_book->book_img == 'No image found')
													<img src="/uploads/no-img.png" width="179" height="246" alt="No image found">
												@else
													<img src="/uploads/{{ $author_book->book_img }}" width="179" height="246" alt="{{ $author_book->book }}">
												@endif
					    					</div>
					    					<div class="recommended-book-detail">
						    					<h6><a href="{{ Route('book.detail', $author_book->slug) }}">{{ Str::limit($author_book->title, 8, '...') }}</a></h6>
						    					<span>By {{ $author->title }}</span>
						    					<ul class="rating-stars">
				    								<li><i class="fa fa-star"></i></li>
				    								<li><i class="fa fa-star"></i></li>
				    								<li><i class="fa fa-star"></i></li>
				    								<li><i class="fa fa-star"></i></li>
				    								<li><i class="fa fa-star-half-o"></i></li>
				    							</ul>
			    							</div>
					    				</div>
					    			</div>
									@endif
									@endforeach
					    			<!-- Product Box -->
					    			

							  	</div>
							  	<!-- Recommended -->

							</div>
							<!-- Widget -->
						</div>
					</div>
					<!-- Content -->
				</div>
			</div>
		</div>
		<!-- Arthor Detail -->
	</main>
@endsection