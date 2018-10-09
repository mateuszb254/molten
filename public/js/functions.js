function kingdom(id) {
    var path = '/images/kingdom/';
    switch (id) {
        case 1:
            path += 'shinsoo.png';
            break;
        case 2:
            path += 'chunjo.png';
            break;
        case 3:
            path += 'jinno.png';
            break;
    }

    return path;
}