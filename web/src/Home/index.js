import React from 'react'
import {useSelector} from 'react-redux'
function Home() {
  const data=useSelector(state=>state.User)
  console.log(data);
    return (
        <div>
            <h1 className="display-1">Home</h1>
            <div id="carouselExampleSlidesOnly" className="carousel slide" data-bs-ride="carousel">
    <div className="carousel-inner">
    <div className="carousel-item active">
      <img src="https://picsum.photos/id/237/200/300" className="d-block w-25" alt="..." />
    </div>
    <div className="carousel-item">
      <img src="https://picsum.photos/id/238/200/300" className="d-block w-25" alt="..." />
    </div>
    <div className="carousel-item">
      <img src="https://picsum.photos/id/257/200/300" className="d-block w-25" alt="..." />
    </div>
  </div>
</div>


        </div>
    )
}

export default Home
