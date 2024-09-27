		<ul class="subject-page">
			<?php $i_page = 1; ?>
			@foreach($subjects as $r)
			<li onclick="showSubjectQuestion('subject_{{$r->id}}');">
				<a href="javascript:void(0);" class="<?php if ($i_page == 1) {echo 'hikari_active active';} ?> hikari-page subject_{{$r->id}} page_{{$i_page}}" data-page="{{$i_page}}" data-mondai="subject_{{$r->id}}">
					<?php echo $i_page; ?>
				</a>
			</li>
			<?php $i_page++; ?>
			@endforeach
		</ul>

		<script type="text/javascript">
			$( document ).ready(function() {
			    $('.hikari-page').on('click', function(){
			    	$(this).addClass('hikari_active');

			    	$('.hikari-page').not(this).removeClass('active');
				    $(this).toggleClass('active');
			    });

			    
			});
		</script>
		
