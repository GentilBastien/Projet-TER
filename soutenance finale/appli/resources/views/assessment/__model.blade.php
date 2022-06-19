@extends('__logout')
@section('model')
    <script src="js/assessment/model.js"></script>
    <script>
        /**
         * Defines the list of globaltags
         */
        {!! $writeglobaltags !!}

        /**
         * Defines the list of wordtags
         */
        {!! $writewordtags !!}

        /**
         * Defines the abstract by getting the parsed abstract
         * and escaping html special chars.
         */
        let parsed_abstract = {!! json_encode($assessment->getParsedAbstract()) !!};

        assessment = new Assessment(parsed_abstract);
    </script>
@endsection
