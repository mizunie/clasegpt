<!doctype html>
<html lang="en" class="h-100">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Chat GPT</title>
        <style>
            .chats{
                background: #fff;
                border-radius: 6px;
                width: 100%;
                height: 83vh;
                overflow-y: auto;
            }
            .mio {
                width: 100%;
                padding: 10px 10px 10px 10%;
            }
            .gpt {
                width: 100%;
                padding: 10px 10% 10px 10px;
            }
            .contenido {
                border: solid 1px #cecece;
                padding: 5px;
                border-radius: 5px;
            }
        </style>
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    </head>
    <body class="h-100 bg-info">
        <div class="d-flex flex-column h-100" id="app">
            <div class="text-white p-3 text-center fw-bold">
                Chat GPT
            </div>
            <div class="p-3 flex-grow-1">
                <div class="chats">
                    <div v-for="m in mensajes" :class="m.clase">
                        <div class="contenido">{{ m.contenido }}</div>
                    </div>
                </div>
            </div>
            <div class="p-3 d-flex" v-if="cargando">
                Cargando
            </div>
            <div class="p-3 d-flex" v-if="!cargando">
                <input type="text" class="form-control rounded-0" v-model="msg" @keyup.enter="chatear">
                <button class="btn btn-success rounded-0" @click="chatear">Enviar</button>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
    <script>
        const app = Vue.createApp({
            components: {},
            watch: {},
            data() {
                return {
                    cargando:false, msg: '', mensajes: [{clase: "gpt", contenido: "Hola soy ChatGPT"}]
                }
            },
            computed: {},
            mounted() {
                this.$nextTick(() => {

                });

            },
            methods: {
                chatear() {
                    this.msg = this.msg.trim();
                    this.cargando=true;
                    if (this.msg.length < 1) {
                        alert("El mensaje no puede tener menos de un caracter");
                        return;
                    }
                    
                    this.mensajes.push({clase: "mio", contenido: this.msg});
                    
                    var fd = new FormData();
                    fd.append("msg",this.msg);
                    
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', "chatea.php", true);
                    xhr.upload.onprogress = function (e) {
                        if (e.lengthComputable) {
                            console.log((e.loaded / e.total) * 100);
                        }
                    };
                    xhr.onload = function () {
                        if (this.status == 200) {
                            app.cargando=false;
                            console.log(this.response);
                            /*app.msg='';
                            
                            let jonson=JSON.parse(this.response);
                            app.mensajes.push({clase: "gpt", contenido: jonson.choices[0].message.content});*/
                        }else{
                            alert("Error "+xhr.status+" "+this.statusText);
                            app.cargando=false;
                        }
                    };
                    xhr.onerror = function () {
                        alert("Error "+xhr.status+" "+this.statusText);
                        app.cargando=false;
                    };

                    xhr.send(fd);
                }
            }
        }).mount("#app");
    </script>
</html>
