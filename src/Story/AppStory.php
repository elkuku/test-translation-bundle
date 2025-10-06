<?php

namespace App\Story;

use App\Factory\ArticleFactory;
use App\Factory\CategoryFactory;
use App\Factory\TagFactory;
use App\Factory\TranslationFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        $space = TagFactory::createOne(['name' => 'space']);
        $food = TagFactory::createOne(['name' => 'food']);
        $mercury = TagFactory::createOne(['name' => 'mercury']);
        $travel = TagFactory::createOne(['name' => 'travel']);
        $science = TagFactory::createOne(['name' => 'science']);
        $time = TagFactory::createOne(['name' => 'time']);

        $article1 = ArticleFactory::createOne([
            'title' => 'Why Asteroids Taste Like Bacon',
            'slug' => 'asteroid',
            'author' => 'Mike Ferengi',
            'publishedAt' => new \DateTimeImmutable('-1 day'),
            'category' => CategoryFactory::new(['name' => 'Space Oddities']),
            'tags' => [$space, $food],
            'content' => <<<EOF
            It sounds like the fever dream of a sci-fi chef: **asteroids that taste like bacon**. But this tasty tale isn’t just a space-faring fantasy — it has roots in real science, with a sizzling sprinkle of imagination.

            ## The Cosmic Chemistry of Flavor

            Let’s start with a surprising truth from the cosmos: many asteroids — particularly **carbonaceous chondrites** — contain **organic compounds**, including **amino acids** and **polycyclic aromatic hydrocarbons (PAHs)**. These complex molecules are the building blocks of life... and also the delicious browning that happens when you sear a steak.

            Ever heard of the **Maillard reaction**? It’s what gives bacon its irresistible taste and aroma. It’s also the result of amino acids reacting with sugars under high heat — a molecular dance that leads to savory, smoky goodness. Now guess what? **Some of the same molecules created by the Maillard reaction are found in space rocks.**

            ## NASA Says: Sniff Before You Bite

            In fact, astronauts and scientists analyzing meteorites have noted that these rocks **smell smoky, like barbecue or welding fumes**. One famous quote came from NASA astrochemist Dr. Max Bernstein, who described the aroma of certain space samples as **“a little like bacon”**.

            So while no one’s firing up the barbecue on the Moon just yet, the building blocks of those meaty aromas are floating around in the solar system — clinging to rocky leftovers from planetary formation.

            ## Interstellar Breakfast? Maybe Not Yet

            Before you get too excited about bacon-flavored space snacks, a few disclaimers:

            - **Tasting asteroids is not recommended** (unless you're a robot).
            - The bacon smell comes from chemicals that aren’t exactly edible — unless your idea of brunch includes formaldehyde and ammonia.
            - There are no reports (yet) of actual crispy strips floating through the asteroid belt.

            ## Still, It’s Kind of Amazing

            In a way, it’s comforting to know that the universe shares our love for the savory. From the hiss of frying pans to the hiss of re-entry capsules, **bacon and space may have more in common than we thought**.

            So the next time you’re enjoying a hearty breakfast, raise a fork to the stars — and toast to the smoky, sizzling science of the universe.

            ---

            **TL;DR:** Some asteroids contain molecules similar to those created when cooking bacon. They smell smoky and meaty because of organic compounds, giving rise to the quirky claim that *"asteroids taste like bacon."*

            ---

            *Disclaimer: Please do not lick meteorites. Your taste buds (and doctor) will thank you.*
            EOF,
        ]);

        TranslationFactory::createOne([
            'locale' => 'fr',
            'objectId' => $article1->getId(),
            'objectType' => 'article',
            'field' => 'title',
            'value' => 'French title...'
        ]);

        TranslationFactory::createOne([
            'locale' => 'fr',
            'objectId' => $article1->getId(),
            'objectType' => 'article',
            'field' => 'content',
            'value' => 'French content...'
        ]);


        ArticleFactory::createOne([
            'title' => 'Life on Planet Mercury: Tan, Relaxing and Fabulous',
            'slug' => 'mercury',
            'author' => 'Amy Oort',
            'publishedAt' => new \DateTimeImmutable('-1 week'),
            'category' => CategoryFactory::new(['name' => 'Astro Travel']),
            'tags' => [$mercury, $space, $travel],
            'content' => <<<EOF
            Move over, Mars. Forget Venus. If you’re looking for the **ultimate cosmic getaway**, there's a new (extremely hot) destination on the block: **Planet Mercury** — where the sun always shines, the tan lines are out of this world, and *"fabulous"* takes on a whole new temperature.

            ## ☀️ The Ultimate Solar Resort

            Mercury is the **closest planet to the Sun**, making it the perfect place to get that deep, space-bronzed glow you’ve always dreamed of — provided your definition of “glow” includes *melting metals and spontaneous combustion*. With daytime temperatures reaching **800°F (430°C)**, you won't need sunscreen — you'll need a **heat-resistant exosuit and a fireproof attitude**.

            ## 💤 No Atmosphere, No Problems

            Tired of Earth’s pesky weather? Mercury has **no atmosphere** to mess with your hair, your plans, or your *vibe*. No wind, no rain, no clouds — just pure, unfiltered solar glamour. Of course, that also means **no oxygen**, but who needs to breathe when you're this fabulous?

            (Answer: you do. Bring a space helmet.)

            ## 🌓 Twice the Day, Twice the Drama

            A day on Mercury (from one sunrise to the next) lasts **176 Earth days**. That means one long, *sun-drenched* stretch of fabulousness followed by an equally long, **freezing cold** night (down to -290°F / -180°C — a great excuse to break out those glittery thermal boots).

            Bonus: Mercury rotates so slowly that **you can literally watch the sun rise, stop, go backward, and rise again**. It’s the kind of dramatic entrance worthy of a celestial soap opera.

            ## 💃 Mercury Fashion Tips

            - **Daywear:** Reflective chrome bodysuits with UV-absorbent capes.
            - **Nightwear:** Triple-layered snowsuits with glowing accessories.
            - **Footwear:** Meteorite-resistant boots (lava soles optional).
            - **Must-have accessory:** A gold-plated sun visor (because you’re THAT extra).

            ## 🪐 Real Talk: Can You Live There?

            Let’s be real. Mercury is **not habitable** by human standards. No atmosphere, no water, huge temperature swings, and relentless radiation. But hey — this is a thought experiment wrapped in glitter and sunflare. If you **could** live there, it would definitely be for the **aesthetic**.

            ## 🌟 Final Thoughts: Hot, Hazardous, and Haute

            Life on Mercury? It's not just survival — it’s a **fashion-forward, solar-powered fantasy**. Sure, you might spontaneously combust and/or freeze within hours, but you’ll do it with style.

            So next time you're planning your interplanetary dream vacation, don't sleep on Mercury. Just... bring a **very** good hat. And maybe a spaceship with AC.

            ---

            **TL;DR:** Life on Mercury is completely uninhabitable, but imagining it as a solar-chic paradise is way more fun than talking about molten iron and deadly radiation. Tan, relaxing, and fabulous — until you evaporate.

            ---

            *Disclaimer: Mercury is not a spa resort. Side effects of visiting include extreme heatstroke, death by vacuum exposure, and spontaneous vaporization. Travel responsibly.*
            EOF,
        ]);

        ArticleFactory::createOne([
            'title' => 'Light Speed Travel: Fountain of Youth or Fallacy',
            'slug' => 'lightspeed',
            'author' => 'Alpha Centauri',
            'publishedAt' => new \DateTimeImmutable('-1 month'),
            'category' => CategoryFactory::new(['name' => 'Bizarre Science']),
            'tags' => [$science, $time, $travel],
            'content' => <<<EOF
            It’s a sci-fi classic: hop aboard a starship, hit light speed, and zip around the galaxy while barely aging a day. Meanwhile, everyone back on Earth gets old, pays taxes, and forgets your Netflix password.

            But is this futuristic fast-forwarding a **fountain of youth** or just a **relativistic illusion**? Let’s dive into the weird, wild physics of light speed and what it really means for aging.

            ## 🧠 Time Dilation: Einstein’s Weird Party Trick

            The idea comes from Einstein’s theory of **special relativity**, which says: the faster you move through space, the slower you move through time — at least from the perspective of someone standing still. This phenomenon is called **time dilation**.

            So if you were zooming near the speed of light, you could theoretically take a round-trip voyage and only experience a few years, while **decades** pass for people back home.

            Sounds like anti-aging, right? Well… sort of.

            ## 👵 Are You Actually Getting Younger?

            Not exactly. Light speed doesn’t **reverse** aging — it just **slows your experience of time** relative to others. Your body still ages naturally according to your own frame of reference. You won’t suddenly get carded at bars or fit into your high school jeans just because you flew past Alpha Centauri.

            You *won’t feel* any younger. But when you return, you may have outlived your entire friend group. (Awkward.)

            ## 🚫 The Speed Limit of the Universe

            Now for the killjoy part: According to current physics, **nothing with mass can reach the speed of light**. As you approach it, your mass becomes infinite, and you'd need **infinite energy** to keep going. So unless we figure out **warp drives**, **wormholes**, or **some mind-melting quantum loophole**, traveling at light speed is a fallacy — at least for now.

            ## ⌛ Real-Life Examples (Kind of)

            We’ve actually seen time dilation happen — just not on dramatic, aging-defying scales. Astronauts on the International Space Station, which orbits Earth at about 17,500 mph, **age a tiny bit slower** than people on the ground. Over six months, they gain about **0.005 seconds** of “youth.”

            So technically, they’re winning. Barely.

            ## 🌌 Final Verdict: Kinda-Sorta Fountain, Mostly Physics Buzzkill

            If you're chasing eternal youth, light speed travel **isn’t your skincare routine**. But as a time-twisting thought experiment, it’s pretty fabulous. You *can* beat the clock — but only if you’re willing to leave your life behind and come back to a very, very different world.

            ---

            **TL;DR:** Traveling near light speed slows down how you experience time, but doesn’t make you younger. It's more "relativity side effect" than "magical anti-aging cure." Cool? Yes. Fountain of youth? Not really.

            ---

            *Disclaimer: Do not attempt to reach light speed in a used minivan. Consult a physicist before manipulating spacetime.*
            EOF,
        ]);
    }
}
