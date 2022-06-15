<section data-view="strate-articles">
    <div class="container">
        <ul>
            <?php
            foreach ($args['items'] as $item) {
                views('card-article', array(
                    'title' => $item['title'],
                ));
            }
            ?>
        </ul>
    </div>
</section>