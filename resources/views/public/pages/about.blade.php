@extends('public.shared.layout')

@php
	$d1 = new DateTime();
	$d2 = new DateTime('1998-02-27');
	$diff = $d2->diff($d1);
@endphp

@section('content')
<div class="container py-5 text-center">
    <div>
        <img class="avatar-lg mb-2" src="https://avatars1.githubusercontent.com/u/25515080?s=460&amp;v=4" alt="Avatar photo of website author.">	           
        <div>
            <span class="font-weight-bold">Marko Rusic</span>
            </p><p><i class="fa fa-envelope-o" aria-hidden="true"></i> marko.rusic.22.17@ict.edu.rs</p>
            <p><i class="fa fa-id-card-o" aria-hidden="true"></i> Broj indeksa: 22/17</p>
        </div>
            {{ 'Imam ' . $diff->y . '. godinu, iz Beograda sam, student druge godine Visoke ICT skole, smer Internet tehnologije. Aktivno se bavim veb programiranjem od treceg razreda srenje skole. Privi programski jezik koji me je ozbiljno zaniteresovao bio je Javascript, tako da sam se u pocetku fokusirao na front end, sve do trenutka u kom sam otkrio Node.js i uopste sve benefite vezane za bekend programiranje. Danas mogu reci za sebe da posedujem znanje u mnogim klijentskim i serverskim tehnologijama.' }}
        </p>
        <div class="mt-3">
            <p>Korisini linkovi:</p>
            <div>
                <a href="https://github.com/markorusic" class="btn btn-brand" style="width: 250px;" target="_blank">
                    <i class="fa fa-github"></i> Github
                </a> 
                <a href="https://www.linkedin.com/in/markorusic/" class="btn btn-brand" style="width: 250px;" target="_blank">
                    <i class="fa fa-linkedin"></i> Linkedin
                </a> 
            </div>
            <hr>
            <p>
                <a class="repo black" href="https://github.com/markorusic/wp2-medium-clone" target="_blank">Repozitorijum ovog sajta</a>
            </p>
            <p>
                <a href="#" target="_blank">Dokumentacija sajta</a>
            </p>
        </div>
    </div>	        
</div>

@endsection