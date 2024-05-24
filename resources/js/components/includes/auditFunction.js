import axios from "axios";


const httpRequest = {

 
  Insert(val) {
    axios({
        method : 'post',
        url : 'audit_insert',
        data: val
    })
    // return Promise.resolve(axios.get(`${baseUrl}/getDataExtraction`), apikey);
  },

}

export default httpRequest;


