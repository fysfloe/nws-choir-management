export default {
    global: {
        methods: {
            urlEncodeArray (array, name) {
                let urlEncodedString = '';
        
                array.map(item => {
                    urlEncodedString += `${name}[]=${item}&`;
                });
        
                return urlEncodedString;
            }
        }
    }
}