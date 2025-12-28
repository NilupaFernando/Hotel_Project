<template>
    <div class="book">
        <div class="ml-11">
            <div class="justify-center space-y-6 font-['Caveat']">
            <p class="text-xl">Book a hotel and earn 1 reward coin per
                Collect 5 coins, </p>
            <p class="text-xl">and enjoy a free hotel stay.</p>
            <p class="text-2xl text-[#D70000]">Start booking now and unlock exclusive benefits.</p>
            </div>
        </div>
        <div class="mx-10 cover"> <!-- Left and right margin -->
        <div class="justify-center space-y-8"> <!-- Vertical gap -->
            <div>
                <p class="font-['Lobster'] text-6xl text-[#D70000]">Special</p>
            </div>
            <div>
                <p class="font-['Lobster'] text-6xl text-[#D70000]">Offer</p>
            </div>
        </div>
        </div>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Black+Ops+One&family=Calistoga&family=Cantora+One&family=Lobster&family=Pacifico&family=Permanent+Marker&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Black+Ops+One&family=Calistoga&family=Cantora+One&family=Caveat:wght@400..700&family=Lobster&family=Pacifico&family=Permanent+Marker&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
.book {
    position: relative;
    border-radius: 10px;
    width: 240px;
    height: 340px;
    background-color: #FFEFD7;
    -webkit-box-shadow: 1px 1px 8px #000;
    box-shadow: 1px 1px 8px #000;
    -webkit-transform: preserve-3d;
    -ms-transform: preserve-3d;
    transform: preserve-3d;
    -webkit-perspective: 2000px;
    perspective: 2000px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    color: #000;
  }
  
  .cover {
    top: 0;
    position: absolute;
    background-color: #FFD700;
    width: 100%;
    height: 100%;
    border-radius: 10px;
    cursor: pointer;
    -webkit-transition: all 0.5s;
    transition: all 0.5s;
    -webkit-transform-origin: 0;
    -ms-transform-origin: 0;
    transform-origin: 0;
    -webkit-box-shadow: 1px 1px 12px #000;
    box-shadow: 1px 1px 12px #000;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
  
  .book:hover .cover {
    -webkit-transition: all 0.5s;
    transition: all 0.5s;
    -webkit-transform: rotatey(-80deg);
    -ms-transform: rotatey(-80deg);
    transform: rotatey(-80deg);
  }
</style>