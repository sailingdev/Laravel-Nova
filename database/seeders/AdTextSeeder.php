<?php

namespace Database\Seeders;

use App\Models\FbReporting\Market;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ad_texts')->delete();
        DB::table('ad_texts')->insert([
            [
                'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Search for {Keyword}',
                'body1' => '🤩 Looking for {Keyword}?  ' . PHP_EOL . PHP_EOL .'Find out our best deals today 👇👇',
                'body2' => '😲 {KEYWORD} 😲 ' .PHP_EOL. PHP_EOL .' Find the best offers today 👇👇'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
                'body1' => '🤩 {Keyword} 🤩' . PHP_EOL . PHP_EOL . '⚫ Time Limited Opportunities' . PHP_EOL . '⚫ Check it out - New offers every day',
                'body2' => 'Search for 👉 {Keyword} 👈 ' . PHP_EOL . PHP_EOL . '⚡ Find Amazing Offers Online ⚡'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
                'body1' => 'TIME LIMITED OPPORTUNITIES '.PHP_EOL.' 😱 Search for {keyword} 😱 ' . PHP_EOL . PHP_EOL . 'Find out more ⬇️⬇️',
                'body2' => 'Search & Save on  👉 {Keyword} 👈 ' . PHP_EOL . PHP_EOL . '⚡Find Amazing Offers Online ⚡'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
                'body1' => 'Search for  👉 {Keyword} 👈 ' . PHP_EOL . 'Find Amazing Offers Online 😲',
                'body2' => 'Search for {Keyword} ✨ ' . PHP_EOL . PHP_EOL . '◉ Find Amazing Offers Online ' . PHP_EOL . '◉ Check it out - New offers every day'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
                'title1' => '⚡SEARCH FOR DISCOUNTS {KEYWORD} ⚡',
                'title2' => '😱SEARCH FOR {KEYWORD} OFFERS😱',
                'body1' => '😲 {KEYWORD} 😲 ' . PHP_EOL . PHP_EOL . 'Limited time discounts ' . PHP_EOL  . '✅Save Money today',
                'body2' => '🤩 {KEYWORD} 🤩 ' . PHP_EOL . PHP_EOL . '⭕ Time Limited Opportunities ' . PHP_EOL . '⭕ Save Money Today'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
                'title1' => '❗SEARCH FOR {Keyword}❗',
                'title2' => '😮Save Money Today😮 - Search for {Keyword}',
                'body1' => '🔥 Looking for {Keyword} ? 🔥' . PHP_EOL . PHP_EOL . 'Time Limited Offers ' . PHP_EOL . 'Discover them today ⬇️',
                'body2' => '🤩 Looking for {Keyword} ? 🤩 ' . PHP_EOL . PHP_EOL . 'Find the BEST Offers Online 😲 ' . PHP_EOL  . 'Check it out today ⬇️⬇️'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
                'title1' => '❗SEARCH FOR {Keyword}❗',
                'title2' => '✨Search for {KEYWORD}✨',
                'body1' => '🔥TIME LIMITED PROMOTIONS 🔥 ' . PHP_EOL . PHP_EOL . 'Looking for {Keyword} ? ' . PHP_EOL  . '✅Discover our best promotions today',
                'body2' => 'Search for 👉 {KEYWORD} 👈 ' . PHP_EOL . PHP_EOL . '🤩Find Amazing Offers Online 🤩'
            ],



            // US start

            [
                'market_id' => Market::select('id')->where('code', 'US')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Search for {Keyword}',
                'body1' => '🤩 Looking for {Keyword}?  ' . PHP_EOL . PHP_EOL .'Find out our best deals today 👇👇',
                'body2' => '😲 {KEYWORD} 😲 ' .PHP_EOL. PHP_EOL .' Find the best offers today 👇👇'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'US')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
                'body1' => '🤩 {Keyword} 🤩' . PHP_EOL . PHP_EOL . '⚫ Time Limited Opportunities' . PHP_EOL . '⚫ Check it out - New offers every day',
                'body2' => 'Search for 👉 {Keyword} 👈 ' . PHP_EOL . PHP_EOL . '⚡ Find Amazing Offers Online ⚡'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'US')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
                'body1' => 'TIME LIMITED OPPORTUNITIES '.PHP_EOL.' 😱 Search for {keyword} 😱 ' . PHP_EOL . PHP_EOL . 'Find out more ⬇️⬇️',
                'body2' => 'Search & Save on  👉 {Keyword} 👈 ' . PHP_EOL . PHP_EOL . '⚡Find Amazing Offers Online ⚡'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'US')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
                'body1' => 'Search for  👉 {Keyword} 👈 ' . PHP_EOL . 'Find Amazing Offers Online 😲',
                'body2' => 'Search for {Keyword} ✨ ' . PHP_EOL . PHP_EOL . '◉ Find Amazing Offers Online ' . PHP_EOL . '◉ Check it out - New offers every day'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'US')->first()['id'],
                'title1' => '⚡SEARCH FOR DISCOUNTS {KEYWORD} ⚡',
                'title2' => '😱SEARCH FOR {KEYWORD} OFFERS😱',
                'body1' => '😲 {KEYWORD} 😲 ' . PHP_EOL . PHP_EOL . 'Limited time discounts ' . PHP_EOL  . '✅Save Money today',
                'body2' => '🤩 {KEYWORD} 🤩 ' . PHP_EOL . PHP_EOL . '⭕ Time Limited Opportunities ' . PHP_EOL . '⭕ Save Money Today'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'US')->first()['id'],
                'title1' => '❗SEARCH FOR {Keyword}❗',
                'title2' => '😮Save Money Today😮 - Search for {Keyword}',
                'body1' => '🔥 Looking for {Keyword} ? 🔥' . PHP_EOL . PHP_EOL . 'Time Limited Offers ' . PHP_EOL . 'Discover them today ⬇️',
                'body2' => '🤩 Looking for {Keyword} ? 🤩 ' . PHP_EOL . PHP_EOL . 'Find the BEST Offers Online 😲 ' . PHP_EOL  . 'Check it out today ⬇️⬇️'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'US')->first()['id'],
                'title1' => '❗SEARCH FOR {Keyword}❗',
                'title2' => '✨Search for {KEYWORD}✨',
                'body1' => '🔥TIME LIMITED PROMOTIONS 🔥 ' . PHP_EOL . PHP_EOL . 'Looking for {Keyword} ? ' . PHP_EOL  . '✅Discover our best promotions today',
                'body2' => 'Search for 👉 {KEYWORD} 👈 ' . PHP_EOL . PHP_EOL . '🤩Find Amazing Offers Online 🤩'
            ],

            // US end





            // ITALY

            [
                'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
                'title1' => '❗Risparmia Oggi❗ - Cerca {Keyword}',
                'title2' => '❗CERCA {KEYWORD}❗',
                'body1' => '🤩 Stai cercando {Keyword}? ' . PHP_EOL . PHP_EOL . '✅ Scopri subito le migliori offerte👇👇',
                'body2' => '😲 {KEYWORD} 😲 ' . PHP_EOL . PHP_EOL . '✅ Scopri le migliori offerte👇👇'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
                'title1' => '❗Risparmia❗ - Cerca i Miglior Risultati per {Keyword}',
                'title2' => '❗CERCA {KEYWORD}❗',
                'body1' => '🤩 {Keyword} 🤩 ' . PHP_EOL . PHP_EOL . '⚫ Offerta speciale ' . PHP_EOL  . '⚫ Tutte le promozioni disponibili',
                'body2' => 'Cerca 👉 {Keyword} 👈 ' . PHP_EOL  . '⚡ Offerte speciali | I migliori prezzi ⚡'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
                'title1' => '❗Risparmia Oggi❗ - Cerca {Keyword}',
                'title2' => '❗CERCA {KEYWORD}❗',
                'body1' => 'OCCASIONI A TEMPO LIMITATO 😱 Cerca {keyword} 😱 ' . PHP_EOL . PHP_EOL . 'Scopri di più ⬇️⬇️',
                'body2' => 'Cerca e Trova 👉 Keyword 👈 ' . PHP_EOL  . '⚡ Offerte speciali | I migliori prezzi ⚡'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
                'title1' => '❗Risparmia❗ - Cerca i Migliori Risultati per {Keyword}',
                'title2' => '❗CERCA {KEYWORD}❗',
                'body1' => 'Trova 👉 {Keyword} 👈 ' . PHP_EOL . 'Ampia Scelta Online 😲',
                'body2' => "Cerca {Keyword} ✨ " . PHP_EOL . PHP_EOL . "◉ Confronta prezzi e caratteristiche " . PHP_EOL . PHP_EOL . "◉ Trova l'offerta migliore"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
                'title1' => '⚡CERCA SCONTI {KEYWORD} ⚡',
                'title2' => '😱CERCA {KEYWORD} 😱',
                'body1' => '😲 {KEYWORD} 😲 ' . PHP_EOL . PHP_EOL . '✅Sconti a tempo limitato ' . PHP_EOL  . '✅Risparmia oggi',
                'body2' => '🤩 {KEYWORD} 🤩 ' . PHP_EOL . PHP_EOL . '⭕ Offerte a tempo limitato ' . PHP_EOL  . '⭕ Risparmia oggi'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
                'title1' => '❗Promozioni Online - Cerca  {Keyword}❗',
                'title2' => '😮Risparmia oggi 😮 - Migliori Offerte {Keyword}',
                'body1' => '🔥 Cerchi {Keyword} ? 🔥 ' . PHP_EOL . PHP_EOL . 'Trova le Migliori Offerte a Tempo Limitato😲 ' . PHP_EOL  . 'Scoprile oggi ⬇️⬇️',
                'body2' => '🤩 Cerchi {Keyword} ? 🤩 ' . PHP_EOL . PHP_EOL . 'Trova le Migliori Offerte Online😲 ' . PHP_EOL  . 'Scoprile oggi ⬇️⬇️'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
                'title1' => '❗CERCA {KEYWORD}❗',
                'title2' => '✨ CERCA OFFERTE {KEYWORD} ✨',
                'body1' => '🔥PROMOZIONI A TEMPO LIMITATO 🔥 ' . PHP_EOL . PHP_EOL . 'Cerchi {Keyword} ? ' . PHP_EOL  . '✅ Scopri oggi le nostre migliori promozioni👇👇
                ',

                'body2' => 'Cerca 👉 {KEYWORD} 👈 ' . PHP_EOL . PHP_EOL . '🤩Trova le Migliori Offerte Online 🤩'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
                'title1' => '❗Heute retten❗ Suche nach {Keyword} Angebote',
                'title2' => '❗SUCHE NACH {KEYWORD} ANGEBOTE❗',
                'body1' => '🤩 Auf der Suche nach {Keyword}? ' . PHP_EOL . PHP_EOL . '✅ Finden Sie die besten Angebote online 👇👇',
                'body2' => '😲 {KEYWORD} 😲 ' . PHP_EOL . PHP_EOL . '✅ Finden Sie die besten Angebote online 👇👇'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
                'title1' => '❗Heute retten❗ Suche nach {Keyword} Angebote',
                'title2' => '❗SUCHE NACH {KEYWORD} ANGEBOTE❗',
                'body1' => '🤩 {Keyword} 🤩 ' . PHP_EOL . PHP_EOL . ' ⚫ Jetzt und nur für kurze zeit ' . PHP_EOL  . '⚫ Entdecken Sie Top Online-Schnäppchen',
                'body2' => 'Finde 👉{Keyword}👈 ' . PHP_EOL  . '⚡ Sonderangebote | Die besten Preise ⚡'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
                'title1' => '❗Heute retten❗ Suche nach {Keyword} Angebote',
                'title2' => '❗SUCHE NACH {KEYWORD} ANGEBOTE❗',
                'body1' => 'ZEITLICH BEGRENZTE ANGEBOTE ' . PHP_EOL  . '😱 Finde {keyword} 😱 ' . PHP_EOL . PHP_EOL . 'Jetzt stöbern! ⬇️⬇️',
                'body2' => 'Suchen Sie nach  👉{Keyword}👈 ' . PHP_EOL  . '⚡ Entdecken Sie Top Online-Schnäppchen ⚡'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
                'title1' => '❗Heute retten❗ Suche nach {Keyword} Angebote',
                'title2' => '❗SUCHE NACH {KEYWORD} ANGEBOTE❗',
                'body1' => 'Finde 👉 {Keyword} 👈 ' . PHP_EOL  . 'Jetzt Entdecken 👇',
                'body2' => 'Finde {Keyword} ✨ ' . PHP_EOL . PHP_EOL . '◉ Sonderangebote | Die besten Preise ' . PHP_EOL  . '◉ Finden Sie Ergebnisse mit unseren Empfehlungen'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
                'title1' => '⚡ SUCHE NACH {KEYWORD} ⚡',
                'title2' => '😱SUCHE NACH {KEYWORD} ANGEBOTE😱',
                'body1' => '😲 {KEYWORD} 😲 ' . PHP_EOL . PHP_EOL . '✅ Zeitlich begrenzte Rabatte ' . PHP_EOL  . '✅ Sparen Sie heute Geld',
                'body2' => '🤩 {KEYWORD} 🤩 ' . PHP_EOL . PHP_EOL . '⭕ Zeitlich begrenzte Angebote ' . PHP_EOL  . '⭕ Heute Geld sparen'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
                'title1' => '❗ Suche Nach {Keyword} ❗',
                'title2' => '😮Heute Geld sparen 😮 - Suche Nach {Keyword}',
                'body1' => '🔥 Auf der Suche nach {Keyword} ? 🔥 ' . PHP_EOL . PHP_EOL . 'Zeitlich begrenzte Angebote ' . PHP_EOL . 'Entdecken Sie sie noch heute ⬇️',
                'body2' => '🤩 Auf der Suche nach {Keyword}? 🤩 ' . PHP_EOL . PHP_EOL . 'Finde die BESTEN Angebote online 😲 ' . PHP_EOL  . 'Schau es dir noch heute an ⬇️⬇️️'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
                'title1' => '❗ SUCHE NACH {KEYWORD}❗',
                'title2' => '✨SUCHE NACH {KEYWORD} ANGEBOTE✨',
                'body1' => '🔥TIME BEGRENZTE WERBEAKTIONEN 🔥 ' . PHP_EOL . PHP_EOL . 'Suchen Sie nach {Keyword} ? ' . PHP_EOL  . '✅ Entdecken Sie noch heute unsere besten Werbeaktionen',
                'body2' => '️Suche nach  👉 {KEYWORD} 👈 ' . PHP_EOL . PHP_EOL . '🤩Erstaunliche Angebote online finden 🤩'
            ],


            [
                'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
                'title1' => '❗GROS RABAIS❗ - Recherche {Keyword}',
                'title2' => '❗RECHERCHE {KEYWORD}❗',
                'body1' => '🤩 Recherche de {keyword} ' . PHP_EOL . PHP_EOL . '✅ Découvrez nos Meilleurs Offres!👇👇',
                'body2' => '️😲 {KEYWORD} 😲 ' . PHP_EOL . PHP_EOL . '✅ Découvrir les meilleures opportunités!👇👇'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
                'title1' => '❗GROS RABAIS❗ - Recherche {Keyword}',
                'title2' => '❗RECHERCHE {KEYWORD}❗',
                'body1' => 'OPPORTUNITÉS LIMITÉES DANS LE TEMPS ' . PHP_EOL  . '😱 Recherche de {keyword} 😱 ' . PHP_EOL . PHP_EOL . 'Découvrez-en plus ! ⬇️⬇️',
                'body2' => '️Recherchez 👉{Keyword}👈  ' . PHP_EOL  . '😍😍 Vente Flash ⚡️ Achetez Maintenant !!'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
                'title1' => '❗GROS RABAIS❗ - Recherche {Keyword}',
                'title2' => '❗RECHERCHE {KEYWORD}❗',
                'body1' => '🤩 {Keyword} 🤩 ' . PHP_EOL . PHP_EOL . '⚫ Découvrir les meilleures opportunités ' . PHP_EOL  . '⚫ Trouver des résultats avec nos choix',
                'body2' => 'Recherchez 👉 {Keyword}👈  ' . PHP_EOL  . '😍😍 Vente Flash ⚡️ Achetez Maintenant !!'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
                'title1' => '❗GROS RABAIS❗ - Recherche {Keyword}',
                'title2' => '❗RECHERCHE {KEYWORD}❗',
                'body1' => 'Trouvez 👉 {Keyword} 👈 ' . PHP_EOL  . 'Meilleures offres en ligne 😲 ' . PHP_EOL . PHP_EOL . '',
                'body2' => 'Recherchez {Keyword} ✨ ' . PHP_EOL . PHP_EOL . '◉ Suggestions et opportunités en ligne.' . PHP_EOL  . '◉ Découvrez nos Meilleurs Offres!'
            ],
            [
                'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
                'title1' => '⚡ RECHERCHE {KEYWORD} ⚡',
                'title2' => '😱RECHERCHE {KEYWORD} OFFRES😱',
                'body1' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅ Offres à durée limitée " . PHP_EOL  . "✅ Économisez de l'argent aujourd'hui",
                'body2' => "🤩 {KEYWORD} 🤩 " . PHP_EOL  . "⭕ Offres limitées dans le temps " . PHP_EOL  . "⭕ Économisez de l'argent dès aujourd'hui"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
                'title1' => '❗ Promotions en ligne {Keyword} ❗',
                'title2' => "😮 Économisez de l'argent dès aujourd'hui 😮 - Meilleures offres {Keyword}",
                'body1' => "🔥 Vous recherchez {Keyword}? 🔥 " . PHP_EOL . PHP_EOL . "Offres limitées dans le temps " . PHP_EOL . PHP_EOL . "Découvrez-les aujourd'hui ⬇️",
                'body2' => "🤩 Vous cherchez {Keyword} ? 🤩 " . PHP_EOL . PHP_EOL . "Trouvez les meilleures offres en ligne 😲 " . PHP_EOL  . "Consultez-le dès aujourd'hui ⬇️⬇️"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
                'title1' => '❗ OFFRES EN LIGNE {KEYWORD} ❗',
                'title2' => "✨{KEYWORD} MEILLEURES OFFRES✨",
                'body1' => "🔥 PROMOTIONS LIMITÉES À TEMPS 🔥 " . PHP_EOL . PHP_EOL . "
                Vous recherchez {Keyword}? " . PHP_EOL  . "✅ Découvrez nos meilleures promotions aujourd'hui",
                'body2' => "Recherchez 👉 {KEYWORD} 👈 🤩Trouvez des offres étonnantes en ligne 🤩"
            ],


            [
                'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
                'title1' => '❗BUSCA {KEYWORD}  OFERTAS ❗',
                'title2' => "❗ Ahorra dinero hoy ❗ - Busca {Keyword}",
                'body1' => "🤩¿Buscando {Keyword}?🤩 " . PHP_EOL . PHP_EOL . "✅ Descubre nuestras mejores ofertas de hoy 👇👇",
                'body2' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅ Encuentra las mejores ofertas de hoy 👇👇"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
                'title1' => '❗BUSCA {KEYWORD}❗',
                'title2' => "❗ Ahorra dinero hoy ❗ - Busca {Keyword}",
                'body1' => "🤩 {Keyword} 🤩 " . PHP_EOL . PHP_EOL . "⚫ Precios por tiempo limitado " . PHP_EOL . "⚫ Compruébalo - Nuevas ofertas todos los días",
                'body2' => "Buscar 👉  {Keyword}  👈 " . PHP_EOL  . "⚡ Encuentra ofertas increíbles online⚡"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
                'title1' => '❗BUSCA {KEYWORD}  OFERTAS ❗',
                'title2' => "❗ Ahorra dinero hoy ❗ - Busca {Keyword}",
                'body1' => "OFERTAS POR TIEMPO LIMITADO " . PHP_EOL  . "😱 Buscar {Keyword} 😱 " . PHP_EOL . PHP_EOL . "Más información ⬇️⬇️",
                'body2' => "Buscar y guardar en 👉 {Keyword} 👈 " . PHP_EOL . "Encuentre ofertas increíbles online"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
                'title1' => '❗BUSCA {KEYWORD}❗',
                'title2' => "❗ Ahorra dinero hoy ❗ - Busca {Keyword}",
                'body1' => "Buscar y ahorrar en  👉{Keyword}👈 " . PHP_EOL  . "Encuentra ofertas increíbles online😲 " . PHP_EOL . PHP_EOL,
                'body2' => "Busca por {Keyword} ✨ " . PHP_EOL . PHP_EOL . "◉ Encuentra ofertas increíbles online ◉ Compruébalo - Nuevas ofertas todos los días"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
                'title1' => '⚡ BUSCA DESCUENTOS {KEYWORD} ⚡',
                'title2' => "😱BUSCA {KEYWORD}😱",
                'body1' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅ Ofertas por tiempo limitado " . PHP_EOL  . "✅ Ahorre dinero hoy",
                'body2' => "🤩 {KEYWORD} 🤩 " . PHP_EOL . PHP_EOL . "⭕ Ofertas por tiempo limitado " . PHP_EOL  . "⭕ Ahorra dinero hoy
                "
            ],
            [
                'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
                'title1' => '❗ Promociones online {Keyword} ❗',
                'title2' => "😮Ahorra dinero hoy 😮 - Mejores Ofertas {Keyword} ",
                'body1' => "🔥 ¿Busca {Keyword}? 🔥 " . PHP_EOL . PHP_EOL . "Ofertas por tiempo limitado " . PHP_EOL  . "Descúbrelos hoy ⬇️",
                'body2' => "🤩 Buscando {Keyword} ? 🤩 " . PHP_EOL . PHP_EOL . "Encuentra las MEJORES ofertas en línea 😲 " . PHP_EOL . "Compruébalo hoy ⬇️⬇️"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
                'title1' => '❗ Promociones online {Keyword} ❗',
                'title2' => "😮Ahorra dinero hoy 😮 - Mejores Ofertas {Keyword} ",
                'body1' => "🔥 ¿Busca {Keyword}? 🔥 " . PHP_EOL . PHP_EOL . "Ofertas por tiempo limitado " . PHP_EOL  . "Descúbrelos hoy ⬇️",
                'body2' => "🤩 Buscando {Keyword} ? 🤩 " . PHP_EOL . PHP_EOL . "Encuentra las MEJORES ofertas en línea 😲 " . PHP_EOL  . "Compruébalo hoy ⬇️⬇️"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
                'title1' => '❗ OFERTAS ONLINE {KEYWORD} ❗',
                'title2' => "✨{KEYWORD} MEJORES OFERTAS ✨",
                'body1' => "🔥 PROMOCIONES POR TIEMPO LIMITADO 🔥 " . PHP_EOL . PHP_EOL . "¿Busca {Keyword}? " . PHP_EOL  . "✅ Descubra nuestras mejores promociones hoy",
                'body2' => "Busca 👉 {KEYWORD} 👈  " . PHP_EOL . PHP_EOL . "🤩 Encuentra ofertas increíbles en línea 🤩"
            ],


            [
                'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
                'title1' => '❗ZOEK VOOR {KEYWORD} AANBIEDINGEN❗',
                'title2' => "❗ Bespaar vandaag❗ - Zoek voor {Keyword} Beste Aanboden ",
                'body1' => "🤩 Op zoek naar {Keyword}? " . PHP_EOL . PHP_EOL . "

                ✅ Ontdek vandaag nog onze beste aanbiedingen 👇👇",

                'body2' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "

                ✅ Vind de beste aanbiedingen vandaag nog 👇👇"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
                'title1' => '❗ZOEK VOOR {KEYWORD}❗',
                'title2' => "❗ Bespaar vandaag❗ - Zoek voor {Keyword}",
                'body1' => "🤩 {Keyword} 🤩 " . PHP_EOL . PHP_EOL . "⚫ Tijdgebonden prijzen " . PHP_EOL  . "⚫ Elke dag nieuwe aanbiedingen",
                'body2' => "Zoek naar 👉 {Keyword} 👈 " . PHP_EOL  . "⚡ Vind geweldige aanbiedingen online ⚡"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
                'title1' => '❗ZOEK VOOR {KEYWORD} AANBIEDINGEN❗',
                'title2' => "❗ Bespaar vandaag❗ - Zoek voor {Keyword} Beste Aanboden",
                'body1' => "TIJD BEPERKTE AANBIEDINGEN " . PHP_EOL  . "😱 Zoeken op {Keyword} 😱 " . PHP_EOL . PHP_EOL . "Ontdek meer ⬇️⬇️️",
                'body2' => "Zoeken en opslaan op 👉 {Keyword}👈  " . PHP_EOL  . "⚡ Vind geweldige aanbiedingen online ⚡"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
                'title1' => '❗ZOEK VOOR {KEYWORD}❗',
                'title2' => "❗ Bespaar vandaag❗ - Zoek voor {Keyword}",
                'body1' => "Zoek naar 👉 {Keyword} 👈 " . PHP_EOL . PHP_EOL . "Vind Geweldige Aanbiedingen online 😲",
                'body2' => "Zoek naar {Keyword} ✨ " . PHP_EOL . PHP_EOL . "◉ Verbazingwekkende prijzen online vinden " . PHP_EOL  . "◉ Bekijk ze eens - Elke dag nieuwe aanbiedingen"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
                'title1' => '⚡ ZOEK VOOR {KEYWORD} ⚡',
                'title2' => "😱ZOEK VOOR {KEYWORD} AANBIEDINGEN😱",
                'body1' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅ Tijdelijke aanbiedingen " . PHP_EOL  . "✅ Bespaar vandaag geld",
                'body2' => "🤩 {KEYWORD} 🤩 " . PHP_EOL . PHP_EOL . "⭕ Aanbiedingen met beperkte tijd " . PHP_EOL  . "⭕ Bespaar vandaag nog geld"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
                'title1' => '❗ Online promoties {Keyword} ❗',
                'title2' => "😮 Bespaar vandaag nog geld 😮 Beste {Keyword} Aanbiedingen",
                'body1' => "🔥 Op zoek naar {Keyword}? 🔥 " . PHP_EOL . PHP_EOL . "Tijdelijke aanbiedingen " . PHP_EOL  . "Ontdek ze vandaag ⬇️",
                'body2' => "🤩 Op zoek naar {Keyword} ? 🤩 " . PHP_EOL . PHP_EOL . "Vind de BESTE AANBODEN Online 😲 " . PHP_EOL  . "Bekijk het vandaag ⬇️⬇️"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
                'title1' => '❗ ONLINE AANBIEDINGEN {KEYWORD} ❗',
                'title2' => "✨{KEYWORD} BESTE AANBODEN✨",
                'body1' => "🔥 TIJD BEPERKTE PROMOTIES 🔥 " . PHP_EOL . PHP_EOL . "Op zoek naar {Keyword}? " . PHP_EOL  . "✅ Ontdek vandaag onze beste promoties",
                'body2' => "Zoek naar👉 {KEYWORD} 👈 " . PHP_EOL . PHP_EOL . "🤩Vind geweldige aanbiedingen online🤩"
            ],



            [
                'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
                'title1' => '❗Spara pengar idag❗ - Sök efter {Keyword}',
                'title2' => "❗SÖK EFTER {KEYWORD} ERBJUDANDE❗",
                'body1' => "🤩 Sök efter {Keyword} " . PHP_EOL . PHP_EOL . "✅ Lär dig mer om det 👇👇",
                'body2' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅ Hitta de bästa erbjudandena online 👇👇"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
                'title1' => '❗Spara pengar idag❗ - Sök efter {Keyword}',
                'title2' => "❗SÖK EFTER {KEYWORD} ERBJUDANDE❗",
                'body1' => "🤩 {Keyword} 🤩 " . PHP_EOL . PHP_EOL . "⚫ Hitta de bästa erbjudandena online " . PHP_EOL  . "⚫ Våra 3 bästa förslag den här månaden.",
                'body2' => "Sök 👉 Keyword 👈  " . PHP_EOL  . "⚡ Jämför erbjudanden, priser och recensioner ⚡"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
                'title1' => '❗Spara pengar idag❗ - Sök efter {Keyword}',
                'title2' => "❗SÖK EFTER {KEYWORD} ERBJUDANDE❗",
                'body1' => "TIDSBEGRÄNSADE MÖJLIGHETER " . PHP_EOL  . "😱 Sök {keyword} 😱 " . PHP_EOL . PHP_EOL . "Lär dig mer om det ⬇️⬇️",
                'body2' => "Sök  👉 Keyword 👈 " . PHP_EOL  . "⚡ Upptäck fler möjligheter online ⚡"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
                'title1' => '❗Spara pengar idag❗ - Sök efter {Keyword}',
                'title2' => "❗SÖK EFTER {KEYWORD} ERBJUDANDE❗",
                'body1' => "Sök  👉 {Keyword} 👈 " . PHP_EOL  . "Toppförslag & möjligheter online 😲 " . PHP_EOL . PHP_EOL,
                'body2' => "Sök {Keyword} ✨ " . PHP_EOL . PHP_EOL . "⚫ Hitta de bästa erbjudandena online" . PHP_EOL . "⚫ Våra 3 bästa förslag den här månaden. "
            ],
            [
                'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
                'title1' => '⚡ SÖK EFTER {KEYWORD} ⚡',
                'title2' => "😱SÖK EFTER {KEYWORD} ERBJUDANDEN😱",
                'body1' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅ Erbjudanden med begränsad tid " . PHP_EOL  . "✅ Spara pengar idag",
                'body2' => "🤩 {KEYWORD} 🤩 " . PHP_EOL . PHP_EOL . "⭕ Tidsbegränsade erbjudanden" . PHP_EOL . "⭕ Spara pengar idag"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
                'title1' => '❗ Online-erbjudanden {Keyword} ❗',
                'title2' => "😮 Bespaar vandaag nog geld 😮 Beste {Keyword} Aanbiedingen",
                'body1' => "🔥 Letar du efter {Keyword}? 🔥" . PHP_EOL . PHP_EOL . "Tidsbegränsade erbjudanden " . PHP_EOL  . "Upptäck dem idag ⬇️",
                'body2' => "🤩 Letar du efter {Keyword}? 🤩 " . PHP_EOL . PHP_EOL . "Hitta de BÄSTA ERBJUDANDEN 😲 " . PHP_EOL . "Kolla in det idag ⬇️⬇️ "
            ],
            [
                'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
                'title1' => '❗ ONLINE ERBJUDANDEN {KEYWORD} ❗',
                'title2' => "✨{KEYWORD} BÄSTA ERBJUDANDEN✨",
                'body1' => "🔥 TIDSBEGRÄNSADE ERBJUDANDEN 🔥 " . PHP_EOL . PHP_EOL . "Letar du efter {Keyword}? " . PHP_EOL  . "✅ Upptäck våra bästa erbjudanden idag",
                'body2' => "Sök efter 👉 {KEYWORD} 👈 " . PHP_EOL . PHP_EOL . "🤩 Hitta fantastiska erbjudanden online 🤩"
            ],


            [
                'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
                'title1' => '❗ SØG EFTER {KEYWORD} ❗',
                'title2' => "❗Gem i Dag❗ - Søg efter {Keyword}",
                'body1' => "🤩 Leder du efter {Keyword}? " . PHP_EOL . PHP_EOL . "✅ Find ud af vores bedste tilbud i dag 👇👇",
                'body2' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅ Find de bedste tilbud i dag 👇👇 "
            ],
            
            [
                'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
                'title1' => '❗ SØG EFTER {KEYWORD} ❗',
                'title2' => "❗Gem i Dag❗ - Søg efter {Keyword}",
                'body1' => "🤩 {Keyword} 🤩 " . PHP_EOL . PHP_EOL . "⚫ Tidsbegrænsede priser " . PHP_EOL . "⚫ Nye tilbud hver dag",
                'body2' => "Søg efter 👉 {Keyword}👈  " . PHP_EOL . "⚡ Find fantastiske Tilbud online ⚡"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
                'title1' => '❗ SØG EFTER {KEYWORD} ❗',
                'title2' => "❗Gem i Dag❗ - Søg efter {Keyword}",
                'body1' => "TIDSBEGRÆNSEDE TILBUD " . PHP_EOL  . "😱 Søg efter {keyword} 😱 " . PHP_EOL . PHP_EOL . "Find ud af mere ⬇️⬇️",
                'body2' => "Søg og spar på 👉 {Keyword}👈  " . PHP_EOL  . "⚡ Find fantastiske tilbud online ⚡ "
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
                'title1' => '❗ SØG EFTER {KEYWORD} ❗',
                'title2' => "❗Gem i Dag❗ - Søg efter {Keyword}",
                'body1' => "Søg efter 👉 {Keyword} 👈 " . PHP_EOL . "Find fantastiske tilbud online 😲",
                'body2' => "Søg efter {Keyword} ✨ " . PHP_EOL . PHP_EOL . "◉ Find fantastiske priser online " . PHP_EOL  . "◉ Tjek dem - Nye tilbud hver dag"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
                'title1' => '⚡ SØG EFTER {KEYWORD} ⚡',
                'title2' => "😱SØG EFTER {KEYWORD} TILBUD😱",
                'body1' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅Begrænsede tilbud " . PHP_EOL  . "✅Spar penge i dag",
                'body2' => "🤩 {KEYWORD} 🤩 " . PHP_EOL . PHP_EOL . "⭕ Tidsbegrænsede tilbud " . PHP_EOL  . "⭕ Spar penge i dag"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
                'title1' => '❗ Online tilbud {Keyword} ❗',
                'title2' => "😮 Spar penge i dag 😮 Bedste {Keyword} tilbud",
                'body1' => "🔥 Leder du efter {Keyword}? 🔥 " . PHP_EOL . PHP_EOL . "Tidsbegrænsede tilbud " . PHP_EOL  . "Oplev dem i dag ⬇️",
                'body2' => "🤩 Leder du efter {Keyword}? 🤩 " . PHP_EOL . PHP_EOL . "Find de BEDSTE TILBUD online😲 " . PHP_EOL  . "Tjek det ud i dag ⬇️⬇️ "
            ],
            [
                'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
                'title1' => '❗ ONLINE TILBUD {KEYWORD} ❗',
                'title2' => "✨{KEYWORD} BEDSTE TILBUD✨",
                'body1' => "🔥 TIDSBEGRÆNSEDE TILBUD 🔥 " . PHP_EOL . PHP_EOL . "Leder du efter {Keyword}? " . PHP_EOL  . "✅ Oplev vores bedste kampagner i dag",
                'body2' => "Søg efte 👉 {KEYWORD} 👈  " . PHP_EOL . PHP_EOL . "🤩 Find fantastiske tilbud online 🤩"
            ],



            [
                'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
                'title1' => '❗SØK ETTER {KEYWORD}❗',
                'title2' => "Lagre i dag ❗ - Søk etter {Keyword} Tilbud",
                'body1' => "🤩 Leter du etter {Keyword}? " . PHP_EOL . PHP_EOL . "✅ Finn ut de beste tilbudene våre i dag 👇👇",
                'body2' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅ Finn de beste tilbudene i dag 👇👇"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
                'title1' => '❗SØK ETTER {KEYWORD}❗',
                'title2' => "Lagre i dag ❗ - Søk etter {Keyword} Tilbud",
                'body1' => "🤩 {Keyword} 🤩 " . PHP_EOL . PHP_EOL . "⚫ Tidsbegrensede priser " . PHP_EOL  . "⚫ Nye tilbud hver dag",
                'body2' => "Søk etter 👉{Keyword}👈  " . PHP_EOL  . "⚡ Finn fantastiske tilbud på nettet ⚡ "
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
                'title1' => '❗SØK ETTER {KEYWORD}❗',
                'title2' => "Lagre i dag ❗ - Søk etter {Keyword} Tilbud",
                'body1' => "TIDSBEGRENSET TILBUD " . PHP_EOL  . "😱 Søk etter {Keyword} 😱 " . PHP_EOL . PHP_EOL . "Finn ut mer ⬇️⬇️",
                'body2' => "Søk og lagre på 👉{Keyword}👈  " . PHP_EOL  . "⚡ Finn fantastiske tilbud på nettet ⚡ "
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
                'title1' => '❗SØK ETTER {KEYWORD}❗',
                'title2' => "Lagre i dag ❗ - Søk etter {Keyword} Tilbud",
                'body1' => "Søk etter 👉{Keyword}👈 " . PHP_EOL  . "Finn fantastiske tilbud online 😲 " . PHP_EOL . PHP_EOL,
                'body2' => "Søk etter {Keyword} ✨ " . PHP_EOL . PHP_EOL . "◉ Finn fantastiske priser online " . PHP_EOL  . "◉ Sjekk dem ut - Nye tilbud hver dag"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
                'title1' => '⚡ SØK ETTER {KEYWORD} ⚡',
                'title2' => "😱SØK ETTER {KEYWORD} TILBUD😱",
                'body1' => "😲 {KEYWORD} 😲 " . PHP_EOL . PHP_EOL . "✅Tidsbegrensede tilbud " . PHP_EOL . "✅ Spar penger i dag",
                'body2' => "🤩 {KEYWORD} 🤩 " . PHP_EOL . PHP_EOL . "⭕ Tidsbegrænsede tilbud " . PHP_EOL  . "⭕ Spar penge i dag"
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
                'title1' => '❗ Online Tilbud {Keyword} ❗',
                'title2' => "😮 Spar penge i dag 😮 Bedste {Keyword} tilbud",
                'body1' => "🔥 Leder du efter {Keyword}? 🔥 " . PHP_EOL . PHP_EOL . "Tidsbegrænsede tilbud " . PHP_EOL  . "Oplev dem i dag ⬇️",
                'body2' => "🤩 Leder du efter {Keyword}? 🤩 " . PHP_EOL . PHP_EOL . "Find de BEDSTE TILBUD online😲 " . PHP_EOL . "Tjek det ud i dag ⬇️⬇️ "
            ],
            [
                'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
                'title1' => '❗ ONLINE TILBUD {KEYWORD} ❗',
                'title2' => "✨{KEYWORD} BESTE TILBUD✨",
                'body1' => "🔥 TIDSBEGRENSEDE TILBUD 🔥 " . PHP_EOL . PHP_EOL . "Leter du etter {Keyword}? " . PHP_EOL  . "Oppdag de beste kampanjene våre i dag",
                'body2' => "Søk etter 👉 {KEYWORD} 👈 " . PHP_EOL . PHP_EOL . "Finn fantastiske tilbud online 🤩"
            ],
        ]); //64
    }
}
