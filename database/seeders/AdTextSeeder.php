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
                'body1' => nl2br('🤩 Looking for {Keyword}?

                    ✅ Find out our best deals today 👇👇'),
                'body2' => nl2br('😲 {KEYWORD} 😲

                ✅ Find the best offers today 👇👇')
            ],
            [
                'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
                'title1' => '❗SEARCH FOR {KEYWORD}❗',
                'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
                'body1' => nl2br('🤩 {Keyword} 🤩

                ⚫ Time Limited Opportunities
                ⚫ Check it out - New offers every day'),
                'body2' => nl2br('Search for 👉 {Keyword} 👈 
                ⚡ Find Amazing Offers Online ⚡')
            ]
        //     [
        //         'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
        //         'title1' => '❗SEARCH FOR {KEYWORD}❗',
        //         'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
        //         'body1' => 'TIME LIMITED OPPORTUNITIES
        //         😱 Search for {keyword} 😱
                
        //         Find out more ⬇️⬇️',
        //         'body2' => 'Search & Save on  👉 {Keyword} 👈 
        //         ⚡Find Amazing Offers Online ⚡'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
        //         'title1' => '❗SEARCH FOR {KEYWORD}❗',
        //         'title2' => '❗Save Money Today❗ - Best {Keyword} Deals',
        //         'body1' => 'Search for  👉 {Keyword} 👈 
        //         Find Amazing Offers Online 😲',
        //         'body2' => 'Search for {Keyword} ✨

        //         ◉ Find Amazing Offers Online
        //         ◉ Check it out - New offers every day'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
        //         'title1' => '⚡SEARCH FOR DISCOUNTS {KEYWORD} ⚡',
        //         'title2' => '😱SEARCH FOR {KEYWORD} OFFERS😱',
        //         'body1' => '😲 {KEYWORD} 😲

        //         ✅Limited time discounts
        //         ✅Save Money today',
        //         'body2' => '🤩 {KEYWORD} 🤩

        //         ⭕ Time Limited Opportunities
        //         ⭕ Save Money Today'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
        //         'title1' => '❗SEARCH FOR {Keyword}❗',
        //         'title2' => '😮Save Money Today😮 - Search for {Keyword}',
        //         'body1' => '🔥 Looking for {Keyword} ? 🔥

        //         Time Limited Offers
        //         Discover them today ⬇️',
        //         'body2' => '🤩 Looking for {Keyword} ? 🤩

        //         Find the BEST Offers Online 😲
        //         Check it out today ⬇️⬇️'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'UK')->first()['id'],
        //         'title1' => '❗SEARCH FOR {Keyword}❗',
        //         'title2' => '✨Search for {KEYWORD}✨',
        //         'body1' => '🔥TIME LIMITED PROMOTIONS 🔥

        //         Looking for {Keyword} ? 
        //         ✅Discover our best promotions today',
        //         'body2' => 'Search for 👉 {KEYWORD} 👈 

        //         🤩Find Amazing Offers Online 🤩'
        //     ],

        //     [
        //         'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
        //         'title1' => '❗Risparmia Oggi❗ - Cerca {Keyword}',
        //         'title2' => '❗CERCA {KEYWORD}❗',
        //         'body1' => '🤩 Stai cercando {Keyword}?

        //         ✅ Scopri subito le migliori offerte👇👇',
        //         'body2' => '😲 {KEYWORD} 😲

        //         ✅ Scopri le migliori offerte👇👇'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
        //         'title1' => '❗Risparmia❗ - Cerca i Miglior Risultati per {Keyword}',
        //         'title2' => '❗CERCA {KEYWORD}❗',
        //         'body1' => '🤩 {Keyword} 🤩

        //         ⚫ Offerta speciale
        //         ⚫ Tutte le promozioni disponibili',
        //         'body2' => 'Cerca 👉 {Keyword} 👈 
        //         ⚡ Offerte speciali | I migliori prezzi ⚡'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
        //         'title1' => '❗Risparmia Oggi❗ - Cerca {Keyword}',
        //         'title2' => '❗CERCA {KEYWORD}❗',
        //         'body1' => 'OCCASIONI A TEMPO LIMITATO 
        //         😱 Cerca {keyword} 😱
                
        //         Scopri di più ⬇️⬇️',
        //         'body2' => 'Cerca e Trova 👉 Keyword 👈 
        //         ⚡ Offerte speciali | I migliori prezzi ⚡'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
        //         'title1' => '❗Risparmia❗ - Cerca i Migliori Risultati per {Keyword}',
        //         'title2' => '❗CERCA {KEYWORD}❗',
        //         'body1' => 'Trova 👉 {Keyword} 👈 
        //         Ampia Scelta Online 😲',
        //         'body2' => "Cerca {Keyword} ✨

        //         ◉ Confronta prezzi e caratteristiche
        //         ◉ Trova l'offerta migliore"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
        //         'title1' => '⚡CERCA SCONTI {KEYWORD} ⚡',
        //         'title2' => '😱CERCA {KEYWORD} 😱',
        //         'body1' => '😲 {KEYWORD} 😲

        //         ✅Sconti a tempo limitato
        //         ✅Risparmia oggi',

        //         'body2' => "🤩 {KEYWORD} 🤩

        //         ⭕ Offerte a tempo limitato
        //         ⭕ Risparmia oggi"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
        //         'title1' => '❗Promozioni Online - Cerca  {Keyword}❗',
        //         'title2' => '😮Risparmia oggi 😮 - Migliori Offerte {Keyword}',
        //         'body1' => '🔥 Cerchi {Keyword} ? 🔥

        //         Trova le Migliori Offerte a Tempo Limitato😲
        //         Scoprile oggi ⬇️⬇️',

        //         'body2' => "🤩 Cerchi {Keyword} ? 🤩

        //         Trova le Migliori Offerte Online😲
        //         Scoprile oggi ⬇️⬇️"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'IT')->first()['id'],
        //         'title1' => '❗CERCA {KEYWORD}❗',
        //         'title2' => '✨ CERCA OFFERTE {KEYWORD} ✨',
        //         'body1' => '🔥PROMOZIONI A TEMPO LIMITATO 🔥 

        //         Cerchi {Keyword} ? 
        //         ✅ Scopri oggi le nostre migliori promozioni👇👇
        //         ',

        //         'body2' => "Cerca 👉 {KEYWORD} 👈 

        //         🤩Trova le Migliori Offerte Online 🤩"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
        //         'title1' => '❗Heute retten❗ Suche nach {Keyword} Angebote',
        //         'title2' => '❗SUCHE NACH {KEYWORD} ANGEBOTE❗',
        //         'body1' => '🤩 Auf der Suche nach {Keyword}?

        //         ✅ Finden Sie die besten Angebote online 👇👇',

        //         'body2' => "😲 {KEYWORD} 😲

        //         ✅ Finden Sie die besten Angebote online 👇👇"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
        //         'title1' => '❗Heute retten❗ Suche nach {Keyword} Angebote',
        //         'title2' => '❗SUCHE NACH {KEYWORD} ANGEBOTE❗',
        //         'body1' => '🤩 {Keyword} 🤩

        //         ⚫ Jetzt und nur für kurze zeit
        //         ⚫ Entdecken Sie Top Online-Schnäppchen',

        //         'body2' => "Finde 👉{Keyword}👈 
        //         ⚡ Sonderangebote | Die besten Preise ⚡"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
        //         'title1' => '❗Heute retten❗ Suche nach {Keyword} Angebote',
        //         'title2' => '❗SUCHE NACH {KEYWORD} ANGEBOTE❗',
        //         'body1' => 'ZEITLICH BEGRENZTE ANGEBOTE 
        //         😱 Finde {keyword} 😱
                
        //         Jetzt stöbern! ⬇️⬇️',

        //         'body2' => 'Suchen Sie nach  👉{Keyword}👈 
        //         ⚡ Entdecken Sie Top Online-Schnäppchen ⚡'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
        //         'title1' => '❗Heute retten❗ Suche nach {Keyword} Angebote',
        //         'title2' => '❗SUCHE NACH {KEYWORD} ANGEBOTE❗',
        //         'body1' => 'Finde 👉 {Keyword} 👈 
        //         Jetzt Entdecken 👇',

        //         'body2' => 'Finde {Keyword} ✨

        //         ◉ Sonderangebote | Die besten Preise
        //         ◉ Finden Sie Ergebnisse mit unseren Empfehlungen'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
        //         'title1' => '⚡ SUCHE NACH {KEYWORD} ⚡',
        //         'title2' => '😱SUCHE NACH {KEYWORD} ANGEBOTE😱',
        //         'body1' => '😲 {KEYWORD} 😲

        //         ✅ Zeitlich begrenzte Rabatte
        //         ✅ Sparen Sie heute Geld',

        //         'body2' => '🤩 {KEYWORD} 🤩

        //         ⭕ Zeitlich begrenzte Angebote
        //         ⭕ Heute Geld sparen'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
        //         'title1' => '❗ Suche Nach {Keyword} ❗',
        //         'title2' => '😮Heute Geld sparen 😮 - Suche Nach {Keyword}',
        //         'body1' => '🔥 Auf der Suche nach {Keyword} ? 🔥

        //         Zeitlich begrenzte Angebote
        //         Entdecken Sie sie noch heute ⬇️',

        //         'body2' => '🤩 Auf der Suche nach {Keyword}? 🤩

        //         Finde die BESTEN Angebote online 😲
        //         Schau es dir noch heute an ⬇️⬇️️'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DE')->first()['id'],
        //         'title1' => '❗ SUCHE NACH {KEYWORD}❗',
        //         'title2' => '✨SUCHE NACH {KEYWORD} ANGEBOTE✨',
        //         'body1' => '🔥TIME BEGRENZTE WERBEAKTIONEN 🔥

        //         Suchen Sie nach {Keyword} ? 
        //         ✅ Entdecken Sie noch heute unsere besten Werbeaktionen',

        //         'body2' => '️Suche nach  👉 {KEYWORD} 👈 

        //         🤩Erstaunliche Angebote online finden 🤩'
        //     ],


        //     [
        //         'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
        //         'title1' => '❗GROS RABAIS❗ - Recherche {Keyword}',
        //         'title2' => '❗RECHERCHE {KEYWORD}❗',
        //         'body1' => '🤩 Recherche de {keyword} 

        //         ✅ Découvrez nos Meilleurs Offres!👇👇',

        //         'body2' => '️😲 {KEYWORD} 😲

        //         ✅ Découvrir les meilleures opportunités!👇👇'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
        //         'title1' => '❗GROS RABAIS❗ - Recherche {Keyword}',
        //         'title2' => '❗RECHERCHE {KEYWORD}❗',
        //         'body1' => 'OPPORTUNITÉS LIMITÉES DANS LE TEMPS
        //         😱 Recherche de {keyword} 😱
                
        //         Découvrez-en plus ! ⬇️⬇️',

        //         'body2' => '️Recherchez 👉{Keyword}👈 
        //         😍😍 Vente Flash ⚡️ Achetez Maintenant !!'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
        //         'title1' => '❗GROS RABAIS❗ - Recherche {Keyword}',
        //         'title2' => '❗RECHERCHE {KEYWORD}❗',
        //         'body1' => '🤩 {Keyword} 🤩

        //         ⚫ Découvrir les meilleures opportunités
        //         ⚫ Trouver des résultats avec nos choix',

        //         'body2' => 'Recherchez 👉 {Keyword}👈 
        //         😍😍 Vente Flash ⚡️ Achetez Maintenant !!'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
        //         'title1' => '❗GROS RABAIS❗ - Recherche {Keyword}',
        //         'title2' => '❗RECHERCHE {KEYWORD}❗',
        //         'body1' => 'Trouvez 👉 {Keyword} 👈 
        //         Meilleures offres en ligne 😲',

        //         'body2' => 'Recherchez {Keyword} ✨

        //         ◉ Suggestions et opportunités en ligne.
        //         ◉ Découvrez nos Meilleurs Offres!'
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
        //         'title1' => '⚡ RECHERCHE {KEYWORD} ⚡',
        //         'title2' => '😱RECHERCHE {KEYWORD} OFFRES😱',
        //         'body1' => "😲 {KEYWORD} 😲

        //         ✅ Offres à durée limitée
        //         ✅ Économisez de l'argent aujourd'hui",

        //         'body2' => "🤩 {KEYWORD} 🤩

        //         ⭕ Offres limitées dans le temps
        //         ⭕ Économisez de l'argent dès aujourd'hui"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
        //         'title1' => '❗ Promotions en ligne {Keyword} ❗',
        //         'title2' => "😮 Économisez de l'argent dès aujourd'hui 😮 - Meilleures offres {Keyword}",
        //         'body1' => "🔥 Vous recherchez {Keyword}? 🔥

        //         Offres limitées dans le temps
        //         Découvrez-les aujourd'hui ⬇️",

        //         'body2' => "🤩 Vous cherchez {Keyword} ? 🤩

        //         Trouvez les meilleures offres en ligne 😲
        //         Consultez-le dès aujourd'hui ⬇️⬇️"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'FR')->first()['id'],
        //         'title1' => '❗ OFFRES EN LIGNE {KEYWORD} ❗',
        //         'title2' => "✨{KEYWORD} 
        //         MEILLEURES OFFRES✨",
        //         'body1' => "🔥 PROMOTIONS LIMITÉES À TEMPS 🔥

        //         Vous recherchez {Keyword}?
        //         ✅ Découvrez nos meilleures promotions aujourd'hui",

        //         'body2' => "Recherchez 👉 {KEYWORD} 👈 

        //         🤩Trouvez des offres étonnantes en ligne 🤩"
        //     ],


        //     [
        //         'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
        //         'title1' => '❗BUSCA {KEYWORD}  OFERTAS ❗',
        //         'title2' => "❗ Ahorra dinero hoy ❗ - Busca {Keyword}",
        //         'body1' => "🤩¿Buscando {Keyword}?🤩

        //         ✅ Descubre nuestras mejores ofertas de hoy 👇👇",

        //         'body2' => "😲 {KEYWORD} 😲

        //         ✅ Encuentra las mejores ofertas de hoy 👇👇"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
        //         'title1' => '❗BUSCA {KEYWORD}❗',
        //         'title2' => "❗ Ahorra dinero hoy ❗ - Busca {Keyword}",
        //         'body1' => "🤩 {Keyword} 🤩

        //         ⚫ Precios por tiempo limitado
        //         ⚫ Compruébalo - Nuevas ofertas todos los días",

        //         'body2' => "Buscar 👉  {Keyword}  👈 
        //         ⚡ Encuentra ofertas increíbles online⚡"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
        //         'title1' => '❗BUSCA {KEYWORD}  OFERTAS ❗',
        //         'title2' => "❗ Ahorra dinero hoy ❗ - Busca {Keyword}",
        //         'body1' => "OFERTAS POR TIEMPO LIMITADO
        //         😱 Buscar {Keyword} 😱
                
        //         Más información ⬇️⬇️",

        //         'body2' => "Buscar y guardar en 👉 {Keyword} 👈
        //         Encuentre ofertas increíbles online"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
        //         'title1' => '❗BUSCA {KEYWORD}❗',
        //         'title2' => "❗ Ahorra dinero hoy ❗ - Busca {Keyword}",
        //         'body1' => "Buscar y ahorrar en  👉{Keyword}👈 
        //         Encuentra ofertas increíbles online😲",

        //         'body2' => "Busca por {Keyword} ✨

        //         ◉ Encuentra ofertas increíbles online ◉ Compruébalo - Nuevas ofertas todos los días"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
        //         'title1' => '⚡ BUSCA DESCUENTOS {KEYWORD} ⚡',
        //         'title2' => "😱BUSCA {KEYWORD}😱",
        //         'body1' => "😲 {KEYWORD} 😲

        //         ✅ Ofertas por tiempo limitado
        //         ✅ Ahorre dinero hoy",

        //         'body2' => "🤩 {KEYWORD} 🤩

        //         ⭕ Ofertas por tiempo limitado
        //         ⭕ Ahorra dinero hoy
        //         "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
        //         'title1' => '❗ Promociones online {Keyword} ❗',
        //         'title2' => "😮Ahorra dinero hoy 😮 - Mejores Ofertas {Keyword} ",
        //         'body1' => "🔥 ¿Busca {Keyword}? 🔥

        //         Ofertas por tiempo limitado
        //         Descúbrelos hoy ⬇️",

        //         'body2' => "🤩 Buscando {Keyword} ? 🤩

        //         Encuentra las MEJORES ofertas en línea 😲
        //         Compruébalo hoy ⬇️⬇️"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
        //         'title1' => '❗ Promociones online {Keyword} ❗',
        //         'title2' => "😮Ahorra dinero hoy 😮 - Mejores Ofertas {Keyword} ",
        //         'body1' => "🔥 ¿Busca {Keyword}? 🔥

        //         Ofertas por tiempo limitado
        //         Descúbrelos hoy ⬇️",

        //         'body2' => "🤩 Buscando {Keyword} ? 🤩

        //         Encuentra las MEJORES ofertas en línea 😲
        //         Compruébalo hoy ⬇️⬇️"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'ES')->first()['id'],
        //         'title1' => '❗ OFERTAS ONLINE {KEYWORD} ❗',
        //         'title2' => "✨{KEYWORD} MEJORES OFERTAS ✨",
        //         'body1' => "🔥 PROMOCIONES POR TIEMPO LIMITADO 🔥

        //         ¿Busca {Keyword}?
        //         ✅ Descubra nuestras mejores promociones hoy",

        //         'body2' => "Busca 👉 {KEYWORD} 👈 

        //         🤩 Encuentra ofertas increíbles en línea 🤩"
        //     ],


        //     [
        //         'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
        //         'title1' => '❗ZOEK VOOR {KEYWORD} AANBIEDINGEN❗',
        //         'title2' => "❗ Bespaar vandaag❗ - Zoek voor {Keyword} Beste Aanboden ",
        //         'body1' => "🤩 Op zoek naar {Keyword}?

        //         ✅ Ontdek vandaag nog onze beste aanbiedingen 👇👇",

        //         'body2' => "😲 {KEYWORD} 😲

        //         ✅ Vind de beste aanbiedingen vandaag nog 👇👇"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
        //         'title1' => '❗ZOEK VOOR {KEYWORD}❗',
        //         'title2' => "❗ Bespaar vandaag❗ - Zoek voor {Keyword}",
        //         'body1' => "🤩 {Keyword} 🤩

        //         ⚫ Tijdgebonden prijzen
        //         ⚫ Elke dag nieuwe aanbiedingen",

        //         'body2' => "Zoek naar 👉 {Keyword} 👈 
        //         ⚡ Vind geweldige aanbiedingen online ⚡
        //         "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
        //         'title1' => '❗ZOEK VOOR {KEYWORD} AANBIEDINGEN❗',
        //         'title2' => "❗ Bespaar vandaag❗ - Zoek voor {Keyword} Beste Aanboden",
        //         'body1' => "TIJD BEPERKTE AANBIEDINGEN
        //         😱 Zoeken op {Keyword} 😱
                
        //         Ontdek meer ⬇️⬇️️
        //         ",

        //         'body2' => "Zoeken en opslaan op 👉 {Keyword}👈 
        //         ⚡ Vind geweldige aanbiedingen online ⚡"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
        //         'title1' => '❗ZOEK VOOR {KEYWORD}❗',
        //         'title2' => "❗ Bespaar vandaag❗ - Zoek voor {Keyword}",
        //         'body1' => "Zoek naar 👉 {Keyword} 👈 

        //         Vind Geweldige Aanbiedingen online 😲",

        //         'body2' => "Zoek naar {Keyword} ✨

        //         ◉ Verbazingwekkende prijzen online vinden
        //         ◉ Bekijk ze eens - Elke dag nieuwe aanbiedingen"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
        //         'title1' => '⚡ ZOEK VOOR {KEYWORD} ⚡',
        //         'title2' => "😱ZOEK VOOR {KEYWORD} AANBIEDINGEN😱",
        //         'body1' => "😲 {KEYWORD} 😲

        //         ✅ Tijdelijke aanbiedingen
        //         ✅ Bespaar vandaag geld",

        //         'body2' => "🤩 {KEYWORD} 🤩

        //         ⭕ Aanbiedingen met beperkte tijd
        //         ⭕ Bespaar vandaag nog geld
        //         "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
        //         'title1' => '❗ Online promoties {Keyword} ❗',
        //         'title2' => "😮 Bespaar vandaag nog geld 😮 Beste {Keyword} Aanbiedingen",
        //         'body1' => "🔥 Op zoek naar {Keyword}? 🔥

        //         Tijdelijke aanbiedingen
        //         Ontdek ze vandaag ⬇️",

        //         'body2' => "🤩 Op zoek naar {Keyword} ? 🤩

        //         Vind de BESTE AANBODEN Online 😲
        //         Bekijk het vandaag ⬇️⬇️"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NL')->first()['id'],
        //         'title1' => '❗ ONLINE AANBIEDINGEN {KEYWORD} ❗',
        //         'title2' => "✨{KEYWORD} BESTE AANBODEN✨",
        //         'body1' => "
        //         🔥 TIJD BEPERKTE PROMOTIES 🔥
                
        //         Op zoek naar {Keyword}?
        //         ✅ Ontdek vandaag onze beste promoties",

        //         'body2' => "Zoek naar👉 {KEYWORD} 👈 

        //         🤩Vind geweldige aanbiedingen online🤩"
        //     ],



        //     [
        //         'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
        //         'title1' => '❗Spara pengar idag❗ - Sök efter {Keyword}',
        //         'title2' => "❗SÖK EFTER {KEYWORD} ERBJUDANDE❗",
        //         'body1' => "🤩 Sök efter {Keyword}

        //         ✅ Lär dig mer om det 👇👇",

        //         'body2' => "😲 {KEYWORD} 😲

        //         ✅ Hitta de bästa erbjudandena online 👇👇"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
        //         'title1' => '❗Spara pengar idag❗ - Sök efter {Keyword}',
        //         'title2' => "❗SÖK EFTER {KEYWORD} ERBJUDANDE❗",
        //         'body1' => "🤩 {Keyword} 🤩

        //         ⚫ Hitta de bästa erbjudandena online
        //         ⚫ Våra 3 bästa förslag den här månaden.",

        //         'body2' => "Sök 👉 Keyword 👈 
        //         ⚡ Jämför erbjudanden, priser och recensioner ⚡"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
        //         'title1' => '❗Spara pengar idag❗ - Sök efter {Keyword}',
        //         'title2' => "❗SÖK EFTER {KEYWORD} ERBJUDANDE❗",
        //         'body1' => "TIDSBEGRÄNSADE MÖJLIGHETER
        //         😱 Sök {keyword} 😱
                
        //         Lär dig mer om det ⬇️⬇️",

        //         'body2' => "Sök  👉 Keyword 👈 
        //         ⚡ Upptäck fler möjligheter online ⚡"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
        //         'title1' => '❗Spara pengar idag❗ - Sök efter {Keyword}',
        //         'title2' => "❗SÖK EFTER {KEYWORD} ERBJUDANDE❗",
        //         'body1' => "Sök  👉 {Keyword} 👈 
        //         Toppförslag & möjligheter online 😲",

        //         'body2' => "Sök {Keyword} ✨

        //         ⚫ Hitta de bästa erbjudandena online
        //         ⚫ Våra 3 bästa förslag den här månaden. "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
        //         'title1' => '⚡ SÖK EFTER {KEYWORD} ⚡',
        //         'title2' => "😱SÖK EFTER {KEYWORD} ERBJUDANDEN😱",
        //         'body1' => "😲 {KEYWORD} 😲

        //         ✅ Erbjudanden med begränsad tid
        //         ✅ Spara pengar idag",

        //         'body2' => "🤩 {KEYWORD} 🤩

        //         ⭕ Tidsbegränsade erbjudanden
        //         ⭕ Spara pengar idag"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
        //         'title1' => '❗ Online-erbjudanden {Keyword} ❗',
        //         'title2' => "😮 Bespaar vandaag nog geld 😮 Beste {Keyword} Aanbiedingen",
        //         'body1' => "🔥 Letar du efter {Keyword}? 🔥

        //         Tidsbegränsade erbjudanden
        //         Upptäck dem idag ⬇️",

        //         'body2' => "🤩 Letar du efter {Keyword}? 🤩

        //         Hitta de BÄSTA ERBJUDANDEN 😲
        //         Kolla in det idag ⬇️⬇️ "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'SE')->first()['id'],
        //         'title1' => '❗ ONLINE ERBJUDANDEN {KEYWORD} ❗',
        //         'title2' => "✨{KEYWORD} BÄSTA ERBJUDANDEN✨",
        //         'body1' => "🔥 TIDSBEGRÄNSADE ERBJUDANDEN 🔥

        //         Letar du efter {Keyword}?
        //         ✅ Upptäck våra bästa erbjudanden idag",

        //         'body2' => "Sök efter 👉 {KEYWORD} 👈

        //         🤩 Hitta fantastiska erbjudanden online 🤩"
        //     ],


        //     [
        //         'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
        //         'title1' => '❗ SØG EFTER {KEYWORD} ❗',
        //         'title2' => "❗Gem i Dag❗ - Søg efter {Keyword}",
        //         'body1' => "🤩 Leder du efter {Keyword}?

        //         ✅ Find ud af vores bedste tilbud i dag 👇👇",

        //         'body2' => "
        //         😲 {KEYWORD} 😲
                
        //         ✅ Find de bedste tilbud i dag 👇👇 "
        //     ],
            
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
        //         'title1' => '❗ SØG EFTER {KEYWORD} ❗',
        //         'title2' => "❗Gem i Dag❗ - Søg efter {Keyword}",
        //         'body1' => "🤩 {Keyword} 🤩

        //         ⚫ Tidsbegrænsede priser
        //         ⚫ Nye tilbud hver dag",

        //         'body2' => "Søg efter 👉 {Keyword}👈 
        //         ⚡ Find fantastiske Tilbud online ⚡"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
        //         'title1' => '❗ SØG EFTER {KEYWORD} ❗',
        //         'title2' => "❗Gem i Dag❗ - Søg efter {Keyword}",
        //         'body1' => "TIDSBEGRÆNSEDE TILBUD
        //         😱 Søg efter {keyword} 😱
                
        //         Find ud af mere ⬇️⬇️",

        //         'body2' => "
        //         Søg og spar på 👉 {Keyword}👈 
        //         ⚡ Find fantastiske tilbud online ⚡ "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
        //         'title1' => '❗ SØG EFTER {KEYWORD} ❗',
        //         'title2' => "❗Gem i Dag❗ - Søg efter {Keyword}",
        //         'body1' => "Søg efter 👉 {Keyword} 👈
        //         Find fantastiske tilbud online 😲",

        //         'body2' => "Søg efter {Keyword} ✨

        //         ◉ Find fantastiske priser online
        //         ◉ Tjek dem - Nye tilbud hver dag"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
        //         'title1' => '⚡ SØG EFTER {KEYWORD} ⚡',
        //         'title2' => "😱SØG EFTER {KEYWORD} TILBUD😱",
        //         'body1' => "😲 {KEYWORD} 😲

        //         ✅Begrænsede tilbud
        //         ✅Spar penge i dag",

        //         'body2' => "🤩 {KEYWORD} 🤩

        //         ⭕ Tidsbegrænsede tilbud
        //         ⭕ Spar penge i dag"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
        //         'title1' => '❗ Online tilbud {Keyword} ❗',
        //         'title2' => "😮 Spar penge i dag 😮 Bedste {Keyword} tilbud",
        //         'body1' => "🔥 Leder du efter {Keyword}? 🔥

        //         Tidsbegrænsede tilbud
        //         Oplev dem i dag ⬇️",

        //         'body2' => "🤩 Leder du efter {Keyword}? 🤩

        //         Find de BEDSTE TILBUD online😲
        //         Tjek det ud i dag ⬇️⬇️ "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'DK')->first()['id'],
        //         'title1' => '❗ ONLINE TILBUD {KEYWORD} ❗',
        //         'title2' => "✨{KEYWORD} BEDSTE TILBUD✨",
        //         'body1' => "🔥 TIDSBEGRÆNSEDE TILBUD 🔥

        //         Leder du efter {Keyword}?
        //         ✅ Oplev vores bedste kampagner i dag",

        //         'body2' => "Søg efte 👉 {KEYWORD} 👈 

        //         🤩 Find fantastiske tilbud online 🤩"
        //     ],



        //     [
        //         'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
        //         'title1' => '❗SØK ETTER {KEYWORD}❗',
        //         'title2' => "Lagre i dag ❗ - Søk etter {Keyword} Tilbud",
        //         'body1' => "🤩 Leter du etter {Keyword}?

        //         ✅ Finn ut de beste tilbudene våre i dag 👇👇",

        //         'body2' => "😲 {KEYWORD} 😲

        //         ✅ Finn de beste tilbudene i dag 👇👇"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
        //         'title1' => '❗SØK ETTER {KEYWORD}❗',
        //         'title2' => "Lagre i dag ❗ - Søk etter {Keyword} Tilbud",
        //         'body1' => "🤩 {Keyword} 🤩

        //         ⚫ Tidsbegrensede priser
        //         ⚫ Nye tilbud hver dag",

        //         'body2' => "Søk etter 👉{Keyword}👈 
        //         ⚡ Finn fantastiske tilbud på nettet ⚡ "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
        //         'title1' => '❗SØK ETTER {KEYWORD}❗',
        //         'title2' => "Lagre i dag ❗ - Søk etter {Keyword} Tilbud",
        //         'body1' => "TIDSBEGRENSET TILBUD
        //         😱 Søk etter {Keyword} 😱
                
        //         Finn ut mer ⬇️⬇️",

        //         'body2' => "Søk og lagre på 👉{Keyword}👈 
        //         ⚡ Finn fantastiske tilbud på nettet ⚡ "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
        //         'title1' => '❗SØK ETTER {KEYWORD}❗',
        //         'title2' => "Lagre i dag ❗ - Søk etter {Keyword} Tilbud",
        //         'body1' => "Søk etter 👉{Keyword}👈
        //         Finn fantastiske tilbud online 😲",

        //         'body2' => "Søk etter {Keyword} ✨

        //         ◉ Finn fantastiske priser online
        //         ◉ Sjekk dem ut - Nye tilbud hver dag"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
        //         'title1' => '⚡ SØK ETTER {KEYWORD} ⚡',
        //         'title2' => "😱SØK ETTER {KEYWORD} TILBUD😱",
        //         'body1' => "😲 {KEYWORD} 😲

        //         ✅Tidsbegrensede tilbud
        //         ✅ Spar penger i dag",

        //         'body2' => "🤩 {KEYWORD} 🤩

        //         ⭕ Tidsbegrænsede tilbud
        //         ⭕ Spar penge i dag"
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
        //         'title1' => '❗ Online Tilbud {Keyword} ❗',
        //         'title2' => "😮 Spar penge i dag 😮 Bedste {Keyword} tilbud",
        //         'body1' => "🔥 Leder du efter {Keyword}? 🔥

        //         Tidsbegrænsede tilbud
        //         Oplev dem i dag ⬇️",

        //         'body2' => "🤩 Leder du efter {Keyword}? 🤩

        //         Find de BEDSTE TILBUD online😲
        //         Tjek det ud i dag ⬇️⬇️ "
        //     ],
        //     [
        //         'market_id' => Market::select('id')->where('code', 'NO')->first()['id'],
        //         'title1' => '❗ ONLINE TILBUD {KEYWORD} ❗',
        //         'title2' => "✨{KEYWORD} BESTE TILBUD✨",
        //         'body1' => "🔥 TIDSBEGRENSEDE TILBUD 🔥

        //         Leter du etter {Keyword}?
        //         ✅ Oppdag de beste kampanjene våre i dag",

        //         'body2' => "Søk etter 👉 {KEYWORD} 👈

        //         Finn fantastiske tilbud online 🤩"
        //     ],
        ]); //64
    }
}
