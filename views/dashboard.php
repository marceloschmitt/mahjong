<?php
/** @var array $user */
/** @var array{members:int,events:int,matches:int,progress:int} $stats */
?>
<section class="page-head">
    <p class="eyebrow">Painel</p>
    <h1>Olá, <?= e(explode(' ', $user['name'])[0]) ?></h1>
    <p class="lede">Este é o ponto de partida da gestão do clube. Os módulos abaixo ainda vão ganhar regras e fluxos.</p>
</section>

<div class="cards">
    <a class="card" href="/membros">
        <span class="card-kicker">Pessoas</span>
        <strong><?= (int) $stats['members'] ?></strong>
        <h2>Membros</h2>
        <p>Cadastro e acompanhamento de quem faz parte do clube.</p>
    </a>
    <a class="card" href="/eventos">
        <span class="card-kicker">Agenda</span>
        <strong><?= (int) $stats['events'] ?></strong>
        <h2>Eventos</h2>
        <p>Torneios, encontros e demais atividades oficiais.</p>
    </a>
    <a class="card" href="/amistosos">
        <span class="card-kicker">Mesas</span>
        <strong><?= (int) $stats['matches'] ?></strong>
        <h2>Amistosos</h2>
        <p>Partidas avulsas, com ou sem vínculo a um evento.</p>
    </a>
    <a class="card" href="/progresso">
        <span class="card-kicker">Acompanhamento</span>
        <strong><?= (int) $stats['progress'] ?></strong>
        <h2>Progresso</h2>
        <p>Notas e histórico de evolução de cada jogador.</p>
    </a>
</div>
