<div id="modalCarousel" class="carousel slide">
	<div class="carousel-inner">
		<div class="carousel-item active">
			{!! Form::label('areaCode', 'Area code: ') !!}
			{!! Form::number('areaCode') !!}
			{!! Form::button('Submit', ['class' => 'btn ajax-numbers', 'href' => '#modalCarousel',  'data-slide' => 'next']) !!}
		</div>
		<div class="carousel-item" id="availableNumbers">
			<table class="table" id="number-table">
				<thead>
					<th>Phone number</th>
					<th>City</th>
					<th></th>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
		<div class="carousel-item" id="number-edit">
			@include('lead_sources.edit')
		</div>
	</div>
</div>