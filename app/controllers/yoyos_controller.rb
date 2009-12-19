class YoyosController < ApplicationController

  def new
    @yoyo = Yoyo.new
  end

  def create
    if current_user.yoyos.create(params[:yoyo])
      flash[:msg] = "Yoyo added to collection successfully"
      redirect_to yoyos_url
    else
      render :action => "new"
    end
  end

  def show
    @yoyo = Yoyo.find(params[:id])
  end

  def edit
    @yoyo = Yoyo.find(params[:id])
  end

  def update
    @yoyo = Yoyo.find(params[:id])
    if @yoyo.update_attributes(params[:yoyo])
      flash[:msg] = "Yoyo saved successfully"
      redirect_to yoyo_url(@yoyo)
    else
      render :action => "edit"
    end
  end

end
