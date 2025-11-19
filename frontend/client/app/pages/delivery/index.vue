<template>
    <div class="container shop-category-area pt-100px pb-100px">
        <h1 class="text-center d-none my-5">Оплата и доставка</h1>
        <section class="mb-5">
            <h2 class="h1 text-center">Оплата</h2>
            <p><strong>Способы оплаты:</strong></p>
            <ul>
                <li>&nbsp;&nbsp;&nbsp;• Банковской картой: по квитанции в отделении любого банка.</li>
                <li>&nbsp;&nbsp;&nbsp;• По СБП: оплата из приложения банка по QR-коду.</li>
                <li>&nbsp;&nbsp;&nbsp;• Через интернет-банк, указав банковские реквизиты нашего счета.</li>
            </ul>
        </section>

        <section class="mb-5">
            <div class="mb-5">
                <h2 class="h1 text-center">Доставка</h2>
                <ul>
                    <li>&nbsp;&nbsp;&nbsp;• Доставка осуществляется силами нашей транспортной компании в течение 1-2
                        дней с момента заказа (при условии наличия товара на складе).</li>
                    <li>&nbsp;&nbsp;&nbsp;• Доставка заказов осуществляется после 100% предоплаты.</li>
                    <li>&nbsp;&nbsp;&nbsp;• Дни доставки: ежедневно, кроме воскресенья.</li>
                    <li>&nbsp;&nbsp;&nbsp;• Время доставки: в течение дня. Мы обязательно свяжемся с вами за
                        час-полтора до примерного времени доставки вашего заказа. Просьба отнестись с пониманием,
                        что в
                        связи с транспортной ситуацией в Москве и области мы не можем заранее указать точное время
                        доставки.</li>
                </ul>
            </div>

            <div class="mb-3">
                <h3 class="h5 text-center">Доставка по Москве и области</h3>
                <p><strong>Внутри МКАД:</strong></p>
                <table>
                    <tbody>
                        <tr>
                            <td style="padding: 0px 20px;">до 100 кг</td>
                            <td>3 000 ₽</td>
                        </tr>
                        <tr>
                            <td style="padding: 0px 20px;">100 - 300 кг</td>
                            <td>3 500 ₽</td>
                        </tr>
                        <tr>
                            <td style="padding: 0px 20px;">300 - 1000 кг</td>
                            <td>4 000 ₽</td>
                        </tr>
                        <tr>
                            <td style="padding: 0px 20px;">свыше 1т</td>
                            <td>3 000 ₽ + 1 ₽/кг</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mb-3">
                <p><strong>За МКАД:</strong> дополнительно 70 руб/км.</p>
            </div>

            <div class="mb-3">
                <h3 class="h5 text-center">Доставка в регионы</h3>
                <p>Каждый день мы отправляем заказы в регионы России и вот как это работает:</p>
                <ul>
                    <li>&nbsp;&nbsp;&nbsp;• <strong>Шаг 1:</strong> Вы выбираете любую удобную для вас транспортную
                        компанию, доставляющую грузы в ваш регион.</li>
                    <li>&nbsp;&nbsp;&nbsp;• <strong>Шаг 2:</strong> Мы отвозим ваш заказ на склад этой транспортной
                        компании в Москве или Московской области.</li>
                    <li>&nbsp;&nbsp;&nbsp;• <strong>Шаг 3:</strong> Через несколько дней вы получаете заказ в своем
                        городе.</li>
                </ul>
            </div>

            <p class="mb-3">Чтобы плитка доехала до вас в целости и сохранности, рекомендуем вам заказать в
                транспортной компании <strong>жесткую упаковку груза</strong> (обрешетку).</p>
            <p class="mb-3">Информацию о том, где находится ваш груз, можно отслеживать на сайте выбранной вами
                транспортной компаний по номеру заявки.</p>
            <p class="mb-5">Более подробную информацию вы можете узнать по номеру телефона <a :href="'tel:' + siteInfoStore.getCleanPhone"
                    class="link-dark">{{ siteInfoStore.getFormattedPhone }}</a> или почтовому ящику <a :href="'mailto:' + siteInfoStore.getEmail"
                    class="link-dark">{{ siteInfoStore.getEmail }}</a>.</p>

            <div class="mb-5">
                <div class="mb-3">
                    <h3 class="h5 text-center">Разгрузка и подъем товара на этаж</h3>
                    <p>Данный вид услуги расчитывается и согласовывается индивидуально.</p>
                    <p><strong>Важно</strong>, чтобы на месте разгрузки небыло препядствий которые затрудняют
                        разгрузку товара, такие как: шлакбаум, автомобили и так далее.</p>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="h1 text-center">Возврат денежных средств</h2>
            <ul style="text-align: justify;">
                <li>&#9;• При оплате картами возврат наличными денежными средствами не допускается.
                    Порядок возврата регулируется правилами международных платежных систем.
                </li>
                <li>&#9;• Для возврата денежных средств на банковскую карту необходимо заполнить
                    «Заявление о возврате денежных средств», которое высылается по требованию компанией на
                    электронный адрес и отправить его вместе с приложением копии паспорта по адресу <a
                        :href="'mailto:' + siteInfoStore.getEmail" class="link-dark">{{ siteInfoStore.getEmail }}</a>.
                </li>
                <li>&#9;• Возврат денежных средств будет осуществлен на банковскую карту в течение 21
                    (двадцати одного) рабочего дня со дня получения «Заявление о возврате денежных
                    средств» Компанией.
                </li>
                <li>&#9;• Для возврата денежных средств по операциям проведенными с ошибками
                    необходимо обратиться с письменным заявлением и приложением копии паспорта и чеков/квитанций,
                    подтверждающих ошибочное списание. Данное заявление необходимо направить по адресу <a
                        :href="'mailto:' + siteInfoStore.getEmail" class="link-dark">{{ siteInfoStore.getEmail }}</a>.
                </li>
                <li>&#9;• Сумма возврата будет равняться сумме покупки. Срок рассмотрения Заявления и
                    возврата денежных средств начинает исчисляться с момента получения Компанией
                    Заявления и рассчитывается в рабочих днях без учета праздников/выходных дней.
                </li>
            </ul>
        </section>
    </div>
</template>

<script setup>
import { useRuntimeConfig } from '#imports'
import { useSiteInfoStore } from '~/stores/useSiteInfoStore'

const config = useRuntimeConfig()
const siteUrl = config.public.siteUrl || 'https://agatceramic.ru'
const siteInfoStore = useSiteInfoStore()

onMounted(async () => {
  try {
    await siteInfoStore.fetchSiteInfo()
  } catch (err) {
    console.error('Failed to load site info:', err)
  }
})

useHead({
    title: 'Оплата и доставка - AgatCeramic',
    meta: [
        {
            name: 'description',
            content: 'Условия оплаты и доставки в интернет-магазине AgatCeramic. Способы оплаты, стоимость доставки по Москве и регионам России.'
        },
        {
            name: 'keywords',
            content: 'оплата, доставка, стоимость доставки, способы оплаты, AgatCeramic, керамическая плитка, сантехника'
        },
        {
            property: 'og:image',
            content: `${siteUrl}/images/stock/logo.png`
        },
        {
            property: 'og:title',
            content: 'AgatCeramic - Интернет-магазин плитки, керамогранита и сантехники'
        },
        {
            property: 'og:description',
            content: 'Условия оплаты и доставки в интернет-магазине AgatCeramic. Способы оплаты, стоимость доставки по Москве и регионам России.'
        },
        {
            property: 'og:url',
            content: `${siteUrl}/delivery`
        },
        {
            name: 'twitter:image',
            content: `${siteUrl}/images/stock/logo.png`
        }
    ],
    link: [
        {
            rel: 'canonical',
            href: `${siteUrl}/delivery`
        }
    ],
    script: [{
        type: 'application/ld+json',
        children: JSON.stringify({
            '@context': 'https://schema.org',
            '@type': 'Organization',
            name: 'AgatCeramic',
            url: siteUrl,
            logo: `${siteUrl}/images/stock/logo.png`,
            description: 'Интернет-магазин плитки, керамогранита и сантехники',
            email: 'zakaz@agatceramic.ru',
            contactPoint: {
                '@type': 'ContactPoint',
                telephone: '+7 (999) 999-99-99',
                contactType: 'customer service'
            }
        })
    }]
})
</script>