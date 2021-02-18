<script>
import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia-min.css';

export default {
    data() {
        return {
            searchClient: algoliasearch(
                '4FSIWOI5U9',
                '8d53a72d01e676ad187d6a95ef216c3d'
            )
        };
    }
};
</script>

<style>
body {
    font-family: sans-serif;
    padding: 1em;
}
</style>
