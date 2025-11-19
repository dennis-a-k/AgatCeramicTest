<template>
  <div class="container pt-100px pb-100px">
    <h1 class="h1 text-center">Обмен и возврат товара</h1>

    <section class="table-return text-center m-lg-5">
      <h2 class="h5">Правила возврата</h2>
      <table class="table">
        <thead>
          <tr>
            <th>Срок приема претензий</th>
            <th>При приемке</th>
            <th>
              После приемки<br /><a href="#7dnei" style="border-bottom: dashed 1px">в течение 7 дней*</a>
            </th>
            <th>
              После приемки<br />
              свыше 7 дней
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td data-label="">Битая плитка, сколы</td>
            <td data-label="При приемке" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки в течение 7 дней" align="center">
              <NuxtImg src="/images/page-return/no.svg" alt="Нет" />
            </td>
            <td data-label="После приемки свыше 7 дней" align="center">
              <NuxtImg src="/images/page-return/no.svg" alt="Нет" />
            </td>
          </tr>
          <tr>
            <td data-label="">Недогруз товара</td>
            <td data-label="При приемке" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки в течение 7 дней" align="center">
              <NuxtImg src="/images/page-return/no.svg" alt="Нет" />
            </td>
            <td data-label="После приемки свыше 7 дней" align="center">
              <NuxtImg src="/images/page-return/no.svg" alt="Нет" />
            </td>
          </tr>
          <tr>
            <td data-label="">
              <a href="#peresort" style="border-bottom: dashed 1px">Пересорт (не тот товар)</a>
            </td>
            <td data-label="При приемке" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки в течение 7 дней" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки свыше 7 дней" align="center">
              <NuxtImg src="/images/page-return/no.svg" alt="Нет" />
            </td>
          </tr>
          <tr>
            <td data-label="">
              <a href="#brak" style="border-bottom: dashed 1px">Заводской брак</a>
            </td>
            <td data-label="При приемке" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки в течение 7 дней" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки свыше 7 дней" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
          </tr>
          <tr>
            <td data-label="">
              <span><a href="#razmer" style="border-bottom: dashed 1px">Материал имеет разный тон/калибр
                  (размер)</a></span>
            </td>
            <td data-label="При приемке" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки в течение 7 дней" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки свыше 7 дней" align="center">
              <NuxtImg src="/images/page-return/no.svg" alt="Нет" />
            </td>
          </tr>
          <tr>
            <td data-label="" style="max-width: 450px">
              <span><a href="#izlishek" style="border-bottom: dashed 1px">Товар не подошел, осталось лишнее (плитка
                  целая, коробки в
                  хорошем состоянии)</a></span>
            </td>
            <td data-label="При приемке" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки в течение 7 дней" align="center">
              <NuxtImg src="/images/page-return/yes.svg" alt="Да" />
            </td>
            <td data-label="После приемки свыше 7 дней" align="center">
              <NuxtImg src="/images/page-return/no.svg" alt="Нет" />
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <section class="mb-5">
      <h2 class="h3 text-center">Правила во время приемки товара</h2>
      <p>
        <strong>При приемке товара, которая происходит в присутствии
          водителя-экспедитора, вам необходимо убедиться, что:</strong>
      </p>
      <ul class="mb-3">
        <li>&nbsp;&nbsp;&nbsp;• Наименование и артикул товара правильные.</li>
        <li>&nbsp;&nbsp;&nbsp;• Количество товара правильное.</li>
        <li>
          &nbsp;&nbsp;&nbsp;• Упаковка товара целая и не имеет повреждений.
        </li>
        <li>&nbsp;&nbsp;&nbsp;• Товар не имеет повреждений и сколов.</li>
      </ul>

      <a name="peresort"></a>

      <p>
        <strong>Внимание! </strong>Вы должны на месте сообщить
        водителю-экспедитору о любых несоответствиях.
      </p>
      <p style="text-align: justify">
        В соответствии со
        <a target="_blank" rel="nofollow"
          href="http://www.consultant.ru/document/cons_doc_LAW_9027/a3dd5614fc46ef5cb3b6d7d88109b6c65cd83be1/#:~:text=1.,%D0%BE%D0%B1%D1%8F%D0%B7%D0%B0%D0%BD%D0%BD%D0%BE%D1%81%D1%82%D1%8C%20%D0%BF%D0%BE%20%D0%BF%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B5%20%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%B0%20%D0%BF%D0%BE%D0%BA%D1%83%D0%BF%D0%B0%D1%82%D0%B5%D0%BB%D1%8E.">ст.
          459 ч.2 ГК РФ</a>
        (также ст.211 ГК РФ), претензии по внешнему виду и комплектности
        доставленного покупателю товара можно предъявить только до момента
        передачи ему товара продавцом.
      </p>
      <p>После приемки претензии по бою и количеству не принимаются.</p>
    </section>

    <section class="mb-5">
      <h2 class="h3 text-center">
        Правило подачи претензии на возврат или замену плитки
      </h2>
      <a name="brak"></a>
      <p><strong>Если вам привезли другой товар (пересорт)</strong></p>
      <ul>
        <li>
          &nbsp;&nbsp;&nbsp;• Cфотографируйте заводскую маркировку на коробках с
          ошибочным товаром.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Отправьте фотографии в письме на нашу почту
          <a :href="'mailto:' + siteInfoStore.getEmail" class="link-dark">{{ siteInfoStore.getEmail }}</a>
          с темой письма <strong>"Ошибка в отгрузке № номер заказа"</strong>.
        </li>
      </ul>
    </section>

    <a name="razmer"></a>
    <section class="mb-5">
      <p><strong>Если вы обнаружили заводской брак</strong></p>
      <ul>
        <li>
          &nbsp;&nbsp;&nbsp;• Сделайте несколько четких фотографий бракованного
          товара вместе и по отдельности.
        </li>
        <li>&nbsp;&nbsp;&nbsp;• Укажите количество бракованного товара.</li>
        <li>
          &nbsp;&nbsp;&nbsp;• Сфотографируйте заводскую маркировку на коробках.
        </li>
        <li>&nbsp;&nbsp;&nbsp;• Опишите брак в свободной форме.</li>
        <li>
          &nbsp;&nbsp;&nbsp;• Отправьте фотографии в письме на нашу почту
          <a :href="'mailto:' + siteInfoStore.getEmail" class="link-dark">{{ siteInfoStore.getEmail }}</a>
          с темой письма <strong>"Брак № номер заказа"</strong>.
        </li>
      </ul>
    </section>

    <a name="izlishek"></a>
    <section class="mb-5">
      <p><strong>Если материал имеет разный тон/калибр (размер)</strong></p>
      <ul>
        <li>
          &nbsp;&nbsp;&nbsp;• Сделайте несколько четких фотографий разнотонного
          товара или отличного по размерам.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Сфотографируйте заводскую маркировку на коробках,
          если плитки одного артикула сильно отличаются по цвету или калибру.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Сообщите сколько плиток (коробок) и какой партии
          необходимо для замены разнотона.
        </li>
        <li>&nbsp;&nbsp;&nbsp;• Опишите брак в свободной форме.</li>
        <li>
          &nbsp;&nbsp;&nbsp;• Отправьте фотографии в письме на нашу почту
          <a :href="'mailto:' + siteInfoStore.getEmail" class="link-dark">{{ siteInfoStore.getEmail }}</a>
          с темой письма <strong>"Разный тон/калибр № номер заказа"</strong>.
        </li>
      </ul>
    </section>

    <section class="mb-5">
      <p><strong>Если товар вам не подошел или остались излишки</strong></p>
      <ul style="text-align: justify">
        <li>
          &nbsp;&nbsp;&nbsp;• Проверьте дату покупки в документах, полученных
          при приемке товара: со дня покупки должно пройти
          <strong>не более 7 календарных дней</strong>.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Убедитесь, что товар укомплектован в запечатанные
          фабричные упаковки.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Отправьте фотографии в письме на нашу почту
          <a :href="'mailto:' + siteInfoStore.getEmail" class="link-dark">{{ siteInfoStore.getEmail }}</a>
          с темой письма <strong>"Возврат № номер заказа"</strong>.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Возврат товара надлежащего качества или излишек
          осуществляется покупателем самостоятельно.
        </li>
        <li>
          <em>
            <strong>
              Приобретение товара с доставкой не дает покупателю права
              требования транспортировки купленного товара в целях замены и
              возврата товара посредством выезда к покупателю. В случаях,
              предусмотренных "Законом о защите прав потребителей..." в
              отношении возврата денежных средств за приобретенный товар,
              денежные средства за предоставленную услугу доставки не
              возвращаются.
            </strong>
          </em>
        </li>
      </ul>
    </section>

    <section class="mb-5">
      <h2 class="h3 text-center">Условия возврата</h2>
      <ul class="mb-3" style="text-align: justify">
        <li>
          &nbsp;&nbsp;&nbsp;• В соответствии со
          <a href="http://www.consultant.ru/document/cons_doc_LAW_305/07e266c138f7d30d498ff235626083e75b770ff3/#:~:text=%D0%9F%D1%80%D0%B0%D0%B2%D0%BE%20%D0%BF%D0%BE%D1%82%D1%80%D0%B5%D0%B1%D0%B8%D1%82%D0%B5%D0%BB%D1%8F%20%D0%BD%D0%B0%20%D0%BE%D0%B1%D0%BC%D0%B5%D0%BD%20%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%B0%20%D0%BD%D0%B0%D0%B4%D0%BB%D0%B5%D0%B6%D0%B0%D1%89%D0%B5%D0%B3%D0%BE%20%D0%BA%D0%B0%D1%87%D0%B5%D1%81%D1%82%D0%B2%D0%B0,-%D0%9F%D0%B5%D1%80%D1%81%D0%BF%D0%B5%D0%BA%D1%82%D0%B8%D0%B2%D1%8B%20%D0%B8%20%D1%80%D0%B8%D1%81%D0%BA%D0%B8&amp;text=1.,%2C%20%D1%80%D0%B0%D1%81%D1%86%D0%B2%D0%B5%D1%82%D0%BA%D0%B5%2C%20%D1%80%D0%B0%D0%B7%D0%BC%D0%B5%D1%80%D1%83%20%D0%B8%D0%BB%D0%B8%20%D0%BA%D0%BE%D0%BC%D0%BF%D0%BB%D0%B5%D0%BA%D1%82%D0%B0%D1%86%D0%B8%D0%B8."
            target="_blank" rel="nofollow">статьей 25 «Право потребителя на обмен товара надлежащего качества»
            Закона РФ «О защите прав потребителей»</a>
          Обмен и возврат непродовольственного товара надлежащего качества
          проводится, если указанный товар не был в употреблении, сохранены его
          товарный вид, потребительские свойства, а также документ,
          подтверждающий факт и условия покупки указанного товара.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Цвет товара на фотографиях может незначительно
          отличаться от реального цвета товара из-за особенностей цветопередачи
          монитора. Указанное обстоятельство не является основанием для
          предъявления каких-либо претензий со стороны покупателя.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Товары с пометкой "Распродажа" в соответствии с
          Законом "О защите прав потребителей" обмену и возврату не подлежат.
        </li>
      </ul>
    </section>

    <a name="7dnei"></a>
    <hr />
    <section style="margin-bottom: 250px">
      <p>
        <strong>* Возврат товара надлежащего качества осуществляется в течение 7 дней
          со дня доставки</strong>
      </p>
      <ul style="text-align: justify">
        <li>
          &nbsp;&nbsp;&nbsp;• Покупка в интернет-магазине относится к
          дистанционным способам продажи, и процедура возврата регулируется
          пунктом
          <a href="http://www.consultant.ru/document/cons_doc_LAW_305/1525b1a2f037db240c8e6a749619f86e53857f13/#:~:text=4.,%D1%82%D0%BE%D0%B2%D0%B0%D1%80%D0%B0%20%2D%20%D0%B2%20%D1%82%D0%B5%D1%87%D0%B5%D0%BD%D0%B8%D0%B5%20%D1%81%D0%B5%D0%BC%D0%B8%20%D0%B4%D0%BD%D0%B5%D0%B9."
            target="_blank" rel="nofollow">пунктом 4 статьи 26.1. Закона «О защите прав потребителей»</a>.
        </li>
        <li>
          &nbsp;&nbsp;&nbsp;• Потребитель вправе отказаться от товара в любое
          время до его передачи, а после передачи товара –
          <strong>в течение семи дней</strong>.
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
  title: 'Обмен и возврат товара - AgatCeramic',
  meta: [
    {
      name: 'description',
      content: 'Правила обмена и возврата товара в интернет-магазине AgatCeramic. Условия возврата, сроки и порядок действий.'
    },
    {
      name: 'keywords',
      content: 'обмен товара, возврат товара, правила возврата, условия возврата, AgatCeramic'
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
      content: 'Правила обмена и возврата товара в интернет-магазине AgatCeramic. Условия возврата, сроки и порядок действий.'
    },
    {
      property: 'og:url',
      content: `${siteUrl}/return`
    },
    {
      name: 'twitter:image',
      content: `${siteUrl}/images/stock/logo.png`
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${siteUrl}/return`
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
      email: siteInfoStore.getEmail,
      contactPoint: {
        '@type': 'ContactPoint',
        telephone: siteInfoStore.getFormattedPhone,
        contactType: 'customer service'
      }
    })
  }]
})
</script>

<style lang="scss">
.table-return table tbody tr td img {
  width: 1.5em;
}
</style>
