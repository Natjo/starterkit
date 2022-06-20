<?php
$currentSearch = str_replace('"', '', get_query_var('s'));
$argsPosts = getSearchCpt([
    "s" => $currentSearch,
    "card" => 'article'
]);
?>

<section data-view="strate-search">
    <div class="container">
        <header>
            <?php $totalPosts = $argsPosts['pager']['total_posts']; ?>
            <?php if ($totalPosts > -1) : ?>
                <?php
                $search = "« " . htmlspecialchars($currentSearch) . " »";
                if ($totalPosts) $title =  $totalPosts . " résultats correspondent à votre recherche " . $search;
                else $title = "Aucun article correspond à votre recherche " . $search;

                ?>
                <h1><?= $title; ?></h1>
            <?php else : ?>
                <div> Merci de saisire une recherche</div>
            <?php endif; ?>
        </header>

        <ul>
            <?php if (!empty($argsPosts['items'])) : ?>
                <?php foreach ($argsPosts['items'] as $item) : ?>
                    <?php views('card-article', $item); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <?php pager($argsPosts['pager'], "/" . "?s=" . htmlspecialchars($currentSearch) . "&paged="); ?>
    </div>
</section>