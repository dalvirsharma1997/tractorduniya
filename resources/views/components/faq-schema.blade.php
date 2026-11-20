@props(['faqs'])

@if($faqs->isNotEmpty())
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($faqs as $faq)
        {
            "@type": "Question",
            "name": {!! json_encode($faq->question) !!},
            "acceptedAnswer": {
                "@type": "Answer",
                "text": {!! json_encode($faq->answer) !!}
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif
